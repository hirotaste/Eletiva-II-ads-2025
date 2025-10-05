

<?php $__env->startSection('title', 'Professores'); ?>

<?php $__env->startSection('page-title', 'Professores'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('teachers.create')); ?>" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Novo Professor
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <?php if(count($teachers) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Especialização</th>
                            <th>Tipo de Vínculo</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($teacher['id']); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                         style="width: 40px; height: 40px; font-size: 16px;">
                                        <?php echo e(strtoupper(substr($teacher['name'], 0, 1))); ?>

                                    </div>
                                    <?php echo e($teacher['name']); ?>

                                </div>
                            </td>
                            <td><?php echo e($teacher['email']); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e($teacher['specialization']); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($teacher['employment_type'] == 'integral' ? 'success' : 'warning'); ?>">
                                    <?php echo e(ucfirst($teacher['employment_type'])); ?>

                                </span>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="<?php echo e(route('teachers.edit', $teacher['id'])); ?>" class="btn btn-outline-primary action-btn" title="Editar Professor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('teachers.destroy', $teacher['id'])); ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este professor?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Professor">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhum professor cadastrado</h5>
                <p class="text-muted">Clique no botão "Novo Professor" para adicionar o primeiro professor.</p>
                <a href="<?php echo e(route('teachers.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Professor
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\PEDR0 Henrique\Documents\FACULDADE\Eletiva-II-ads-2025\resources\views/teachers/index.blade.php ENDPATH**/ ?>