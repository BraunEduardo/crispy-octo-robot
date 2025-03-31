# crispy-octo-robot > Docker

## Sumário
- [Requisitos](#requisitos)
- [Configuração](#configuração)
- [Inicialização](#inicialização)
- [Acesso](#acesso)

## Requisitos

- Docker e Docker Compose:
```bash
apt update && apt upgrade
apt install -y ca-certificates curl gnupg
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor > /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg
echo "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | tee /etc/apt/sources.list.d/docker.list
apt update && apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

## Configuração

- Configurações do '.env':
```bash
cp -a .env-template .env
# Edite os parâmetros conforme desejado
```

## Inicialização

- Build e up do ambiente:
```bash
COMPOSE_BAKE=true docker compose up -d
```

## Acesso
- A aplicação estará acessível no localhost com a porta específicada (default 80)
