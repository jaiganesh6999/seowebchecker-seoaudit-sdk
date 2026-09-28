param(
    [string]$ServerUrl = "https://seowebchecker.jfrog.io/artifactory",
    [string]$Token = $env:JFROG_ACCESS_TOKEN,
    [string]$Username = $env:JFROG_USER,
    [string]$Password = $env:JFROG_PASSWORD,
    [string]$Repo = "generic-local"
)

$ErrorActionPreference = "Stop"

if (-not $Token -and (-not $Username -or -not $Password)) {
    Write-Host "Please provide authentication for JFrog Artifactory:" -ForegroundColor Yellow
    Write-Host "  Option 1: -Token '<YOUR_ACCESS_TOKEN>' (or `$env:JFROG_ACCESS_TOKEN)" -ForegroundColor Cyan
    Write-Host "  Option 2: -Username '<USER>' -Password '<PASSWORD_OR_API_KEY>'" -ForegroundColor Cyan
    Write-Host "Generate an Access Token at: $ServerUrl (User Profile -> Access Tokens)" -ForegroundColor Gray
    exit 1
}

# Setup headers
$headers = @{}
if ($Token) {
    $headers["Authorization"] = "Bearer $Token"
} else {
    $authBytes = [System.Text.Encoding]::UTF8.GetBytes("${Username}:${Password}")
    $authBase64 = [System.Convert]::ToBase64String($authBytes)
    $headers["Authorization"] = "Basic $authBase64"
}

# Test connection
Write-Host "Testing connection to $ServerUrl..." -ForegroundColor Cyan
try {
    $ping = Invoke-WebRequest -Uri "$ServerUrl/api/system/ping" -UseBasicParsing
    if ($ping.StatusCode -eq 200) {
        Write-Host "Artifactory is online and reachable!" -ForegroundColor Green
    }
} catch {
    Write-Error "Failed to reach Artifactory at $ServerUrl: $_"
    exit 1
}

# List of all package files to deploy
$artifacts = @(
    @{ Path = "python/dist/seowebchecker_seoaudit_sdk-1.0.0-py3-none-any.whl"; Target = "python/seowebchecker_seoaudit_sdk-1.0.0-py3-none-any.whl" },
    @{ Path = "python/dist/seowebchecker_seoaudit_sdk-1.0.0.tar.gz"; Target = "python/seowebchecker_seoaudit_sdk-1.0.0.tar.gz" },
    @{ Path = "dotnet/nupkg/SeoWebChecker.SeoAudit.1.0.0.nupkg"; Target = "nuget/SeoWebChecker.SeoAudit.1.0.0.nupkg" },
    @{ Path = "ruby/seowebchecker-seoaudit-sdk-1.0.0.gem"; Target = "ruby/seowebchecker-seoaudit-sdk-1.0.0.gem" },
    @{ Path = "chocolatey/seowebchecker.1.0.0.nupkg"; Target = "chocolatey/seowebchecker.1.0.0.nupkg" },
    @{ Path = "conda/dist/noarch/seowebchecker-seoaudit-sdk-1.0.0-py_0.tar.bz2"; Target = "conda/seowebchecker-seoaudit-sdk-1.0.0-py_0.tar.bz2" },
    @{ Path = "haskell/dist/seowebchecker-1.0.0.tar.gz"; Target = "haskell/seowebchecker-1.0.0.tar.gz" },
    @{ Path = "r/seowebchecker_1.0.0.tar.gz"; Target = "r/seowebchecker_1.0.0.tar.gz" }
)

Write-Host "Deploying artifacts to repository: $Repo..." -ForegroundColor Cyan

foreach ($art in $artifacts) {
    if (Test-Path $art.Path) {
        $targetUri = "$ServerUrl/$Repo/$($art.Target)"
        Write-Host "Uploading $($art.Path) -> $targetUri" -ForegroundColor Gray
        try {
            $response = Invoke-RestMethod -Uri $targetUri -Method Put -InFile $art.Path -Headers $headers
            Write-Host " -> Successfully deployed $($art.Target)" -ForegroundColor Green
        } catch {
            Write-Host " -> Upload error on $($art.Target): $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

Write-Host "`nAll deployment tasks completed for JFrog Artifactory!" -ForegroundColor Green
Write-Host "Browse your repository at: $ServerUrl/#/artifacts/browse/tree/General/$Repo" -ForegroundColor Yellow
