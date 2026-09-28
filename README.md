# seowebchecker-seoaudit-sdk

[![PyPI version](https://img.shields.io/pypi/v/seowebchecker-seoaudit-sdk.svg?color=blue)](https://pypi.org/project/seowebchecker-seoaudit-sdk/)
[![npm version](https://img.shields.io/npm/v/seowebchecker-seoaudit-sdk.svg?color=red)](https://www.npmjs.com/package/seowebchecker-seoaudit-sdk)
[![NuGet](https://img.shields.io/nuget/v/SeoWebChecker.SeoAudit.svg?color=blue)](https://www.nuget.org/packages/SeoWebChecker.SeoAudit/)
[![Packagist](https://img.shields.io/packagist/v/seowebchecker/seoaudit-sdk.svg?color=orange)](https://packagist.org/packages/seowebchecker/seoaudit-sdk)
[![RubyGems](https://badge.fury.io/rb/seowebchecker-seoaudit-sdk.svg)](https://rubygems.org/gems/seowebchecker-seoaudit-sdk)
[![CRAN](https://img.shields.io/badge/CRAN-seowebchecker-276DC3.svg)](https://cran.r-project.org)
[![Hackage](https://img.shields.io/hackage/v/seowebchecker.svg?color=purple)](https://hackage.haskell.org/package/seowebchecker)
[![Go Reference](https://pkg.go.dev/badge/github.com/jaiganesh6999/seowebchecker-seoaudit-sdk.svg)](https://pkg.go.dev/github.com/jaiganesh6999/seowebchecker-seoaudit-sdk)
[![Clojars Project](https://img.shields.io/clojars/v/net.clojars.seoaitools/seowebchecker-seoaudit-sdk.svg)](https://clojars.org/net.clojars.seoaitools/seowebchecker-seoaudit-sdk)
[![Crates.io](https://img.shields.io/crates/v/seowebchecker-seoaudit-sdk.svg)](https://crates.io/crates/seowebchecker-seoaudit-sdk)
[![Julia](https://img.shields.io/badge/Julia-SeoWebCheckerAudit-purple.svg)](https://juliahub.com)
[![pub package](https://img.shields.io/pub/v/seowebchecker.svg)](https://pub.dev/packages/seowebchecker)
[![Hex.pm](https://img.shields.io/hexpm/v/seowebchecker.svg)](https://hex.pm/packages/seowebchecker)
[![LuaRocks](https://img.shields.io/luarocks/v/seowebchecker/seowebchecker.svg)](https://luarocks.org/modules/seowebchecker/seowebchecker)
[![Conda](https://img.shields.io/badge/Conda-seowebchecker--seoaudit--sdk-3EB049.svg)](https://anaconda.org)
[![CocoaPods](https://img.shields.io/cocoapods/v/SeoWebChecker.svg)](https://cocoapods.org/pods/SeoWebChecker)
[![Swift Package Index](https://img.shields.io/badge/Swift%20Package%20Index-SeoWebChecker-FA7343.svg)](https://swiftpackageindex.com/jaiganesh6999/seowebchecker-seoaudit-sdk)
[![Bower](https://img.shields.io/badge/Bower-seowebchecker-FFCC29.svg)](https://bower.io)
[![Docker Pulls](https://img.shields.io/docker/pulls/seowebchecker/seoaudit-sdk.svg)](https://hub.docker.com/r/seowebchecker/seoaudit-sdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![SEOWebChecker](https://img.shields.io/badge/Official%20Site-seowebchecker.com-indigo)](https://seowebchecker.com/)

A lightweight, multi-language open-source client SDK and CLI suite for full website SEO audits, on-page optimization, and Core Web Vitals checks.

Official website: **[https://seowebchecker.com/](https://seowebchecker.com/)**

---

## 📦 Multi-Ecosystem Distribution Matrix

| Registry | Language / Ecosystem | Package Identifier | Package Directory |
| :--- | :--- | :--- | :--- |
| **[CRAN](https://cran.r-project.org)** | R (>= 3.5) | `seowebchecker` | [`r/`](./r) |
| **[NPM](https://npmjs.com)** | Node.js & TypeScript | `seowebchecker-seoaudit-sdk` | [`npm/`](./npm) |
| **[Docker Hub](https://hub.docker.com)** | Containers & CLI | `seowebchecker/seoaudit-sdk` | [`docker/`](./docker) |
| **[PyPI](https://pypi.org)** | Python 3.8+ | `seowebchecker-seoaudit-sdk` | [`python/`](./python) |
| **[Anaconda.org](https://anaconda.org)** | Conda / Python / Data Science | `seowebchecker-seoaudit-sdk` | [`conda/`](./conda) |
| **[Maven Central](https://central.sonatype.com)** | Java 11+ | `com.seowebchecker:seowebchecker-seoaudit-sdk` | [`java/`](./java) |
| **[pkg.go.dev](https://pkg.go.dev/github.com/jaiganesh6999/seowebchecker-seoaudit-sdk)** | Go (>= 1.20) | `github.com/jaiganesh6999/seowebchecker-seoaudit-sdk` | [Root (`./`)](./) |
| **[pub.dev](https://pub.dev/packages/seowebchecker)** | Dart & Flutter (>= 3.0) | `seowebchecker` | [`dart/`](./dart) |
| **[Hex.pm](https://hex.pm/packages/seowebchecker)** | Elixir & Erlang (BEAM) | `seowebchecker` | [`elixir/`](./elixir) |
| **[CocoaPods](https://cocoapods.org/pods/SeoWebChecker)** | iOS, macOS, watchOS, tvOS (Swift) | `SeoWebChecker` | [`swift/`](./swift) |
| **[LuaRocks](https://luarocks.org/modules/seowebchecker/seowebchecker)** | Lua (>= 5.1) & LuaJIT | `seowebchecker` | [`lua/`](./lua) |
| **[RubyGems](https://rubygems.org)** | Ruby 2.7+ | `seowebchecker-seoaudit-sdk` | [`ruby/`](./ruby) |
| **[NuGet](https://nuget.org)** | .NET / C# | `SeoWebChecker.SeoAudit` | [`dotnet/`](./dotnet) |
| **[Packagist](https://packagist.org)** | PHP 8.2+ | `seowebchecker/seoaudit-sdk` | [`php/`](./php) |
| **[CPAN](https://metacpan.org)** | Perl 5 | `SeoWebChecker::SeoAudit` | [`perl/`](./perl) |
| **[Crates.io](https://crates.io)** | Rust | `seowebchecker-seoaudit-sdk` | [`rust/`](./rust) |
| **[Hackage](https://hackage.haskell.org)** | Haskell | `seowebchecker` | [`haskell/`](./haskell) |
| **[JuliaHub](https://juliahub.com)** | Julia 1.6+ | `SeoWebCheckerAudit` | [`julia/`](./julia) |
| **[Clojars](https://clojars.org)** | Clojure / JVM | `net.clojars.seoaitools/seowebchecker-seoaudit-sdk` | [`clojure/`](./clojure) |
| **[Bower](https://bower.io)** | Front-End & Web Assets | `seowebchecker` (`jaiganesh6999/seowebchecker-seoaudit-sdk`) | [Root (`./bower.json`)](./bower.json) |
| **[Swift Package Index](https://swiftpackageindex.com/jaiganesh6999/seowebchecker-seoaudit-sdk)** | Swift & Apple Platforms (SPM) | `SeoWebChecker` | [Root (`./Package.swift`)](./Package.swift) |

*Step-by-step instructions for building and publishing to all 21 registries are in **[PUBLISHING_GUIDE.md](./PUBLISHING_GUIDE.md)**.*

---

## 🖥️ Local Interactive Web Dashboard

To run the interactive local test dashboard:

```powershell
python demo_server.py
```
*(Or double click `start_frontend.bat`)*

Then open **[http://localhost:5000](http://localhost:5000)** in your browser.

---

## 📄 License

MIT License © 2026 [SEOWebChecker.com](https://seowebchecker.com/).
