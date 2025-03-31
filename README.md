# crispy-octo-robot

## Sumário
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Rotas](#rotas)
- [Pendências](#pendências)
- [Considerações](#considerações)

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

## Instalação
Detalhes sobre a instalação estão documentados em [docker/README.md](docker/README.md)

## Rotas
A documentação completa das rotas está disponível em [crispy-octo-robot.postman_collection.json](crispy-octo-robot.postman_collection.json)

## Pendências
- Validação por roles
- Reembolso de transação

## Considerações
- Me sentiria mais confortável tendo liberdade para estruturação total do banco de dados
- Falta documentação sobre erros dos gateways