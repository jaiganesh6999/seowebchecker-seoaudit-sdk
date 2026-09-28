{
  description = "Lightweight website SEO audit tool and CLI by SEOWebChecker (https://seowebchecker.com/)";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = import nixpkgs { inherit system; };
      in
      rec {
        packages.seowebchecker = pkgs.stdenv.mkDerivation rec {
          pname = "seowebchecker";
          version = "1.0.1";

          src = ./npm;

          nativeBuildInputs = [ pkgs.makeWrapper ];
          buildInputs = [ pkgs.nodejs ];

          installPhase = ''
            runHook preInstall

            mkdir -p $out/lib/node_modules/seowebchecker-seoaudit-sdk $out/bin
            cp -r * $out/lib/node_modules/seowebchecker-seoaudit-sdk/

            makeWrapper ${pkgs.nodejs}/bin/node $out/bin/seowebchecker \
              --add-flags "$out/lib/node_modules/seowebchecker-seoaudit-sdk/bin/cli.js"

            ln -s $out/bin/seowebchecker $out/bin/seowebchecker-audit

            runHook postInstall
          '';

          meta = with pkgs.lib; {
            description = "Lightweight website SEO audit tool and CLI by SEOWebChecker";
            homepage = "https://seowebchecker.com/";
            license = licenses.mit;
            mainProgram = "seowebchecker";
            platforms = platforms.all;
          };
        };

        packages.default = packages.seowebchecker;

        apps.seowebchecker = flake-utils.lib.mkApp {
          drv = packages.seowebchecker;
          name = "seowebchecker";
        };
        apps.default = apps.seowebchecker;

        devShells.default = pkgs.mkShell {
          buildInputs = with pkgs; [
            nodejs
            python3
          ];
        };
      }
    );
}
