
# 🛒 Mini ERP de Pedidos - Laravel

Este é um sistema simples de pedidos com controle de produtos, cupons de desconto, carrinho de compras e envio de e-mail de confirmação de pedido. Foi desenvolvido como teste técnico para avaliação de habilidades em Laravel, MySQL e integração com serviços externos como Mailtrap e ViaCEP.

---

## 🚀 Funcionalidades

- ✅ Cadastro e edição de produtos com variação de tamanho
- ✅ Carrinho de compras com controle de quantidade
- ✅ Aplicação de cupons com:
  - Código exclusivo
  - Desconto fixo
  - Data de validade
  - Valor mínimo para ativação
- ✅ Cálculo automático de:
  - Subtotal
  - Desconto
  - Frete
  - Total com desconto
- ✅ Preenchimento automático do endereço via CEP (API ViaCEP)
- ✅ Finalização de pedido com persistência no banco
- ✅ Envio de e-mail de confirmação para o cliente (via Mailtrap)
- ✅ Front-end responsivo com Bootstrap

---

## 🧪 Tecnologias Utilizadas

- PHP 8.x
- Laravel 10.x
- MySQL
- Bootstrap 5
- Mailtrap (para testes de e-mail)
- API ViaCEP (busca de endereço)
- HTML + Blade + JavaScript

---

## 📦 Instalação do Projeto

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/mini-erp-pedidos.git
cd mini-erp-pedidos
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Configure o arquivo `.env`

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o banco de dados no `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_erp
DB_USERNAME=root
DB_PASSWORD=
```

> 💡 Crie o banco manualmente se ainda não existir:
```sql
CREATE DATABASE mini_erp;
```

### 5. Execute as migrações

```bash
php artisan migrate
```

---

## ✉️ Configuração de E-mail (Mailtrap)

1. Crie uma conta gratuita em [https://mailtrap.io/](https://mailtrap.io/)
2. Copie suas credenciais SMTP
3. No arquivo `.env`, adicione:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=SEU_USUARIO
MAIL_PASSWORD=SUA_SENHA
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@teste.com
MAIL_FROM_NAME="Loja Leonardo"
```

---

## ▶️ Como executar

```bash
php artisan serve
```

Abra no navegador: [http://localhost:8000](http://localhost:8000)

---

## 🧪 Roteiro de Teste

1. Cadastre um **produto** com variação de tamanho
2. Adicione o produto ao **carrinho**
3. Cadastre um **cupom** com valor mínimo e validade
4. Acesse o **checkout**
5. Aplique o cupom
6. Preencha o **endereço de entrega**
7. Finalize o pedido com um **e-mail válido**
8. Verifique o e-mail no [Mailtrap](https://mailtrap.io/)

---

## 🧩 Recursos Avançados

- 🔍 **Aplicação de cupom via AJAX** com retorno de erros amigável
- 🔄 **Atualização automática** dos valores de desconto e total no checkout
- 🏷️ **Identificação do cupom aplicado** e possibilidade de remoção
- 📩 **Confirmação do pedido enviada por e-mail** com total e itens

---

## 📸 Capturas de Tela

> ⚠️ Inclua screenshots aqui, se desejar

---

## 👨‍💻 Autor

**Leonardo Barreiro**  
Desenvolvedor PHP / Laravel  
[LinkedIn](https://www.linkedin.com/in/seu-perfil) • [GitHub](https://github.com/seu-usuario)

---

## 📄 Licença

Este projeto é livre para uso e modificação, apenas para fins acadêmicos e de teste técnico.

---

## 📌 Observações

- O cupom só é aplicado se:
  - O código for válido
  - Não estiver expirado
  - O valor mínimo do pedido for atingido
- O e-mail de confirmação **não chega na sua caixa de entrada real**, pois é capturado pelo **Mailtrap**
- O campo de CEP traz automaticamente o endereço via **API ViaCEP**

---
