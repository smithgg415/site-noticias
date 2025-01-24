<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        footer {
            background-color: #4b2a9b;
            color: white;
            padding: 40px 20px;
            margin-top: 40px;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        footer .footer-links div {
            flex: 1;
            min-width: 200px;
            margin-bottom: 20px;
        }

        footer .footer-links h5 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            text-decoration: underline;
            color: #a02ae1;
        }

        footer .footer-social-icons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        footer .footer-social-icons a {
            color: white;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        footer .footer-social-icons a:hover {
            color: #a02ae1;
        }

        .footer-bottom {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 20px;
            color: #ddd;
        }

        @media (max-width: 768px) {
            footer .footer-links {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-links div {
                text-align: center;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            footer .footer-links h5 {
                font-size: 1rem;
            }

            footer .footer-links a {
                font-size: 0.9rem;
            }

            footer .footer-social-icons a {
                font-size: 1.2rem;
            }

            .footer-bottom {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <footer>
        <div class="container footer-links">
            <div>
                <h5>Sobre nós</h5>
                <p>Somos uma plataforma de notícias dedicada a trazer o melhor conteúdo de forma clara e objetiva.</p>
            </div>
            <div>
                <h5>Contato</h5>
                <p>Email: contato@infonews.com.br</p>
                <p>Telefone: (11) 1234-5678</p>
            </div>
            <div>
                <h5>Links úteis</h5>
                <a href="perfil.php?openModal=true&tab=screen2">Política e Diretrizes</a>

                <a href="#">FAQ</a>
            </div>
        </div>

        <div class="footer-social-icons">
            <a href="https://www.facebook.com" target="_blank" class="bi bi-facebook"></a>
            <a href="https://twitter.com" target="_blank" class="bi bi-twitter"></a>
            <a href="https://www.instagram.com" target="_blank" class="bi bi-instagram"></a>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 INFONEWS. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>