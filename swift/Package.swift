// swift-tools-version:5.5
import PackageDescription

let package = Package(
    name: "SeoWebChecker",
    platforms: [
        .iOS(.v12),
        .macOS(.v10_13),
        .watchOS(.v4),
        .tvOS(.v12)
    ],
    products: [
        .library(
            name: "SeoWebChecker",
            targets: ["SeoWebChecker"]
        ),
    ],
    dependencies: [],
    targets: [
        .target(
            name: "SeoWebChecker",
            dependencies: [],
            path: "Sources/SeoWebChecker"
        ),
        .testTarget(
            name: "SeoWebCheckerTests",
            dependencies: ["SeoWebChecker"],
            path: "Tests/SeoWebCheckerTests"
        ),
    ]
)
