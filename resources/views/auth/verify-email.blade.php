<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar E-mail - Sistema de Gerenciamento</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container-box {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header h2 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #6c757d;
            font-size: 0.95rem;
        }
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }
        .btn-logout {
            background: #6c757d;
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
            color: white;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container-box">
        <div class="header">
            <i class="fas fa-envelope-open-text fa-3x mb-3" style="color: #667eea;"></i>
            <h2>Verificar E-mail</h2>
            <p>Obrigado por se registrar! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar? Se você não recebeu o e-mail, teremos prazer em enviar outro.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success mb-3">
                <i class="fas fa-check-circle me-2"></i>Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
            </div>
        @endif

        <div class="d-flex justify-content-between gap-2 mt-4">
            <form method="POST" action="{{ route('verification.send') }}" class="flex-grow-1">
                @csrf
                <button type="submit" class="btn btn-verify">
                    <i class="fas fa-paper-plane me-2"></i>Reenviar E-mail de Verificação
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Sair
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
