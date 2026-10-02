# SEOWebChecker Bower Registration & Verification Script
# Official Website: https://seowebchecker.com/

$PackageName = "seowebchecker"
$RepoUrl = "https://github.com/jaiganesh6999/seowebchecker-seoaudit-sdk.git"
$RegistryUrl = "https://registry.bower.io/packages"

Write-Host "=========================================================" -ForegroundColor Cyan
Write-Host "  SEOWebChecker: Bower.io Package Registration Script     " -ForegroundColor Cyan
Write-Host "=========================================================`n" -ForegroundColor Cyan

# 1. Check if package already registered
Write-Host "[1/3] Checking Bower registry for '$PackageName'..." -ForegroundColor Yellow
try {
    $existing = Invoke-RestMethod -Uri "$RegistryUrl/$PackageName" -Method Get -ErrorAction Stop
    Write-Host "Package '$PackageName' is already registered on Bower:" -ForegroundColor Green
    Write-Host "  Name: $($existing.name)"
    Write-Host "  URL:  $($existing.url)"
    Write-Host "`nTo install in your web project:" -ForegroundColor Cyan
    Write-Host "  bower install $PackageName --save`n" -ForegroundColor White
    exit 0
} catch {
    Write-Host "Package '$PackageName' is not yet registered on Bower." -ForegroundColor Yellow
}

# 2. Attempt registration
Write-Host "`n[2/3] Registering '$PackageName' on Bower registry..." -ForegroundColor Yellow
Write-Host "  Repository: $RepoUrl"

# Check if bower CLI is available
$bowerCli = Get-Command bower -ErrorAction SilentlyContinue

if ($bowerCli) {
    Write-Host "Found bower CLI. Running: bower register $PackageName $RepoUrl" -ForegroundColor Cyan
    & bower register $PackageName $RepoUrl
} else {
    Write-Host "Bower CLI not found in PATH. Submitting directly to Bower REST API..." -ForegroundColor Cyan
    try {
        $body = @{
            name = $PackageName
            url  = $RepoUrl
        } | ConvertTo-Json

        $response = Invoke-RestMethod -Uri $RegistryUrl -Method Post -Body $body -ContentType "application/json"
        Write-Host "Successfully registered '$PackageName' on Bower!" -ForegroundColor Green
        Write-Host ($response | Format-List | Out-String)
    } catch {
        Write-Host "Bower API response: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host "`nIf the remote repo needs git tags pushed first, push a semver tag:" -ForegroundColor Yellow
        Write-Host "  git tag v1.0.0"
        Write-Host "  git push origin v1.0.0"
        Write-Host "`nThen register via:"
        Write-Host "  bower register $PackageName $RepoUrl" -ForegroundColor White
    }
}

# 3. Verification
Write-Host "`n[3/3] Verifying registration..." -ForegroundColor Yellow
try {
    $verify = Invoke-RestMethod -Uri "$RegistryUrl/$PackageName" -Method Get -ErrorAction Stop
    Write-Host "Verification PASSED! '$PackageName' is live on Bower registry." -ForegroundColor Green
} catch {
    Write-Host "Note: Once your git repository tag is pushed to GitHub, run:" -ForegroundColor Yellow
    Write-Host "  bower register $PackageName $RepoUrl" -ForegroundColor White
}
