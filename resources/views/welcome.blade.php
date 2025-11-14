<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento Educacional</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        .features {
            text-align: left;
            margin: 2rem 0;
        }
        .feature {
            margin: 0.5rem 0;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .api-links {
            margin-top: 2rem;
        }
        .api-link {
            display: inline-block;
            margin: 0.5rem;
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .api-link:hover {
            background: #5a6fd8;
        }
        .dashboard-link {
            background: #28a745 !important;
            font-size: 1.2rem !important;
            padding: 15px 30px !important;
            margin: 20px 0 !important;
            display: block !important;
            width: 300px;
            margin: 20px auto !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Sistema de Gerenciamento Educacional</h1>
        <p>Sistema desenvolvido para gerenciar uma instituição de ensino superior.</p>
        
        <div class="features">
            <div class="feature">
                <strong>👨‍🏫 Professores:</strong> Gerenciamento completo de docentes
            </div>
            <div class="feature">
                <strong>📚 Disciplinas:</strong> Cadastro e controle de matérias
            </div>
            <div class="feature">
                <strong>🏫 Salas de Aula:</strong> Gestão de espaços físicos
            </div>
            <div class="feature">
                <strong>👨‍🎓 Estudantes:</strong> Controle de alunos matriculados
            </div>
            <div class="feature">
                <strong>📋 Matrículas:</strong> Sistema de inscrições
            </div>
            <div class="feature">
                <strong>🗓️ Matriz Curricular:</strong> Estrutura dos cursos
            </div>
        </div>

        <div class="api-links">
            <a href="/login" class="api-link dashboard-link">
                🔐 FAZER LOGIN
            </a>
            <a href="/dashboard" class="api-link dashboard-link">
                🚀 ACESSAR SISTEMA COMPLETO
            </a>
        </div>
        
        <div class="api-links">
            <h3>API Endpoints (Demonstração)</h3>
            <a href="/api/demo/teachers" class="api-link">👨‍🏫 Professores</a>
            <a href="/api/demo/disciplines" class="api-link">📚 Disciplinas</a>
            <a href="/api/demo/classrooms" class="api-link">🏫 Salas</a>
            <a href="/api/demo/students" class="api-link">👨‍🎓 Estudantes</a>
            <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                💡 <strong>Nota:</strong> Este projeto está funcionando em modo demonstração com dados simulados.<br>
                Para funcionalidade completa, configure um banco de dados (MySQL/PostgreSQL/SQLite).
            </p>
        </div>
        
        <p style="margin-top: 2rem; color: #666; font-size: 0.9rem;">
            Laravel {{ app()->version() }} | PHP {{ PHP_VERSION }}
        </p>
    </div>
</body>
</html>