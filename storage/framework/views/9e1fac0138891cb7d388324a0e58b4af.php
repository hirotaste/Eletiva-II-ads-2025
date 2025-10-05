<!DOCTYPE html><!DOCTYPE html>

<html lang="pt-BR"><html lang="pt-BR">

<head><head>

    <meta charset="UTF-8">    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Gerenciamento Educacional</title>    <title>Sistema de Gerenciamento Educacional</title>

    <style>    <style>

        body {        body {

            font-family: Arial, sans-serif;            font-family: Arial, sans-serif;

            margin: 0;            margin: 0;

            padding: 0;            padding: 0;

            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

            min-height: 100vh;            min-height: 100vh;

            display: flex;            display: flex;

            align-items: center;            align-items: center;

            justify-content: center;            justify-content: center;

        }        }

        .container {        .container {

            background: white;            background: white;

            padding: 2rem;            padding: 2rem;

            border-radius: 10px;            border-radius: 10px;

            box-shadow: 0 10px 30px rgba(0,0,0,0.3);            box-shadow: 0 10px 30px rgba(0,0,0,0.3);

            text-align: center;            text-align: center;

            max-width: 600px;            max-width: 600px;

        }        }

        h1 {        h1 {

            color: #333;            color: #333;

            margin-bottom: 1rem;            margin-bottom: 1rem;

        }        }

        .features {        .features {

            text-align: left;            text-align: left;

            margin: 2rem 0;            margin: 2rem 0;

        }        }

        .feature {        .feature {

            margin: 0.5rem 0;            margin: 0.5rem 0;

            padding: 0.5rem;            padding: 0.5rem;

            background: #f8f9fa;            background: #f8f9fa;

            border-radius: 5px;            border-radius: 5px;

            border-left: 4px solid #667eea;            border-left: 4px solid #667eea;

        }        }

        .api-links {        .api-links {

            margin-top: 2rem;            margin-top: 2rem;

        }        }

        .api-link {        .api-link {

            display: inline-block;            display: inline-block;

            margin: 0.5rem;            margin: 0.5rem;

            padding: 0.5rem 1rem;            padding: 0.5rem 1rem;

            background: #667eea;            background: #667eea;

            color: white;            color: white;

            text-decoration: none;            text-decoration: none;

            border-radius: 5px;            border-radius: 5px;

            transition: background 0.3s;            transition: background 0.3s;

        }        }

        .api-link:hover {        .api-link:hover {

            background: #5a6fd8;            background: #5a6fd8;

        }        }

        .dashboard-link {    </style>

            background: #28a745 !important;</head>

            font-size: 1.2rem !important;<body>

            padding: 15px 30px !important;    <div class="container">

            margin: 20px 0 !important;        <h1>🎓 Sistema de Gerenciamento Educacional</h1>

            display: block !important;        <p>Sistema desenvolvido para gerenciar uma instituição de ensino superior.</p>

            width: 300px;        

            margin: 20px auto !important;        <div class="features">

        }            <div class="feature">

    </style>                <strong>👨‍🏫 Professores:</strong> Gerenciamento completo de docentes

</head>            </div>

<body>            <div class="feature">

    <div class="container">                <strong>📚 Disciplinas:</strong> Cadastro e controle de matérias

        <h1>🎓 Sistema de Gerenciamento Educacional</h1>            </div>

        <p>Sistema desenvolvido para gerenciar uma instituição de ensino superior.</p>            <div class="feature">

                        <strong>🏫 Salas de Aula:</strong> Gestão de espaços físicos

        <div class="features">            </div>

            <div class="feature">            <div class="feature">

                <strong>👨‍🏫 Professores:</strong> Gerenciamento completo de docentes                <strong>👨‍🎓 Estudantes:</strong> Controle de alunos matriculados

            </div>            </div>

            <div class="feature">            <div class="feature">

                <strong>📚 Disciplinas:</strong> Cadastro e controle de matérias                <strong>📋 Matrículas:</strong> Sistema de inscrições

            </div>            </div>

            <div class="feature">            <div class="feature">

                <strong>🏫 Salas de Aula:</strong> Gestão de espaços físicos                <strong>🗓️ Matriz Curricular:</strong> Estrutura dos cursos

            </div>            </div>

            <div class="feature">        </div>

                <strong>👨‍🎓 Estudantes:</strong> Controle de alunos matriculados

            </div>        <div class="api-links">

            <div class="feature">            <h3>API Endpoints (Demonstração)</h3>

                <strong>📋 Matrículas:</strong> Sistema de inscrições            <a href="/api/demo/teachers" class="api-link">👨‍🏫 Professores</a>

            </div>            <a href="/api/demo/disciplines" class="api-link">📚 Disciplinas</a>

            <div class="feature">            <a href="/api/demo/classrooms" class="api-link">🏫 Salas</a>

                <strong>🗓️ Matriz Curricular:</strong> Estrutura dos cursos            <a href="/api/demo/students" class="api-link">👨‍🎓 Estudantes</a>

            </div>            <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">

        </div>                💡 <strong>Nota:</strong> Este projeto está funcionando em modo demonstração com dados simulados.<br>

                Para funcionalidade completa, configure um banco de dados (MySQL/PostgreSQL/SQLite).

        <div class="api-links">            </p>

            <a href="/dashboard" class="api-link dashboard-link">        </div>

                🚀 ACESSAR SISTEMA COMPLETO        

            </a>        <p style="margin-top: 2rem; color: #666; font-size: 0.9rem;">

                        Laravel <?php echo e(app()->version()); ?> | PHP <?php echo e(PHP_VERSION); ?>


            <h3>API Endpoints (Demonstração)</h3>        </p>

            <a href="/api/demo/teachers" class="api-link">👨‍🏫 Professores</a>    </div>

            <a href="/api/demo/disciplines" class="api-link">📚 Disciplinas</a></body>

            <a href="/api/demo/classrooms" class="api-link">🏫 Salas</a></html>
            <a href="/api/demo/students" class="api-link">👨‍🎓 Estudantes</a>
            <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                💡 <strong>Nota:</strong> Este projeto está funcionando em modo demonstração com dados simulados.<br>
                Para funcionalidade completa, configure um banco de dados (MySQL/PostgreSQL/SQLite).
            </p>
        </div>
        
        <p style="margin-top: 2rem; color: #666; font-size: 0.9rem;">
            Laravel <?php echo e(app()->version()); ?> | PHP <?php echo e(PHP_VERSION); ?>

        </p>
    </div>
</body>
</html><?php /**PATH C:\Users\PEDR0 Henrique\Documents\FACULDADE\Eletiva-II-ads-2025\resources\views/welcome.blade.php ENDPATH**/ ?>