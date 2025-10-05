<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> - Sistema Educacional</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            left: 0;
            top: 0;
            width: 16.66667%; /* col-md-2 width */
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: white;
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.3);
            color: white;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
            margin-left: 16.66667%; /* Compensar sidebar fixa */
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-custom {
            border-radius: 20px;
            padding: 8px 20px;
        }
        .navbar-brand {
            font-weight: bold;
            color: #764ba2 !important;
        }
        .action-btn {
            min-width: 38px !important;
            min-height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 6px !important;
            transition: all 0.2s ease !important;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .action-buttons-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .stats-card {
            min-width: 190px !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .main-content .container-fluid {
            padding-right: 70px !important;
        }
        @media (max-width: 1149px) {
            .stats-row {
                display: flex;
                flex-wrap: wrap;
            }
            .stats-row .col-md-3 {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }
        
        /* Estilos para UL da navegação */
        .nav.flex-column {
            align-content: center;
        }
        
        /* Menu lateral colapsado para telas menores que 930px */
        @media (max-width: 929px) {
            .sidebar {
                width: 60px !important;
                min-width: 60px !important;
            }
            .sidebar .text-center {
                padding: 1rem 0.5rem !important;
                text-align: center !important;
            }
            .sidebar .text-center h4 {
                font-size: 1.5rem !important;
                margin: 0 !important;
                text-align: center !important;
            }
            .sidebar .text-center .nav-text {
                display: none !important;
            }
            .sidebar .nav-link {
                padding: 12px 8px !important;
                text-align: center !important;
                position: relative;
                margin: 2px 0 !important;
            }
            .sidebar .nav-link .nav-text {
                display: none !important;
            }
            .sidebar .nav-link i {
                margin-right: 0 !important;
                font-size: 1.1rem !important;
            }
            .main-content {
                margin-left: 60px !important;
            }
        }
        
        /* Tooltip customizado */
        .nav-tooltip {
            position: absolute;
            left: 65px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #333;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
            z-index: 1000;
            pointer-events: none;
        }
        
        .nav-tooltip::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #333;
        }
        
        .nav-link:hover .nav-tooltip {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white">
                        <span class="d-inline">🎓</span><span class="nav-text"> Sistema Educacional</span>
                    </h4>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            <span class="nav-text">Dashboard</span>
                            <div class="nav-tooltip">Dashboard</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('teachers.index')); ?>">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            <span class="nav-text">Professores</span>
                            <div class="nav-tooltip">Professores</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('students.index')); ?>">
                            <i class="fas fa-user-graduate me-2"></i>
                            <span class="nav-text">Estudantes</span>
                            <div class="nav-tooltip">Estudantes</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('disciplines.index')); ?>">
                            <i class="fas fa-book me-2"></i>
                            <span class="nav-text">Disciplinas</span>
                            <div class="nav-tooltip">Disciplinas</div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('classrooms.index')); ?>">
                            <i class="fas fa-door-open me-2"></i>
                            <span class="nav-text">Salas de Aula</span>
                            <div class="nav-tooltip">Salas de Aula</div>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main content -->
            <main class="col-md-10 ms-sm-auto main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $__env->yieldContent('page-title'); ?></h1>
                    <?php echo $__env->yieldContent('page-actions'); ?>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\PEDR0 Henrique\Documents\FACULDADE\Eletiva-II-ads-2025\resources\views/layouts/app.blade.php ENDPATH**/ ?>