

<?php $__env->startSection('title', 'Disciplinas'); ?>

<?php $__env->startSection('page-title', 'Disciplinas'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('disciplines.create')); ?>" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Nova Disciplina
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <?php if(count($disciplines) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Carga Horária</th>
                            <th>Créditos</th>
                            <th>Tipo</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $disciplines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $discipline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($discipline['id']); ?></td>
                            <td><code><?php echo e($discipline['code']); ?></code></td>
                            <td><?php echo e($discipline['name']); ?></td>
                            <td><?php echo e($discipline['workload_hours']); ?>h (<?php echo e($discipline['weekly_hours']); ?>h/semana)</td>
                            <td><strong><?php echo e($discipline['credits']); ?></strong></td>
                            <td>
                                <span class="badge bg-<?php echo e($discipline['type'] == 'obrigatória' ? 'success' : ($discipline['type'] == 'eletiva' ? 'info' : 'warning')); ?>">
                                    <?php echo e(ucfirst($discipline['type'])); ?>

                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('disciplines.edit', $discipline['id'])); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('disciplines.destroy', $discipline['id'])); ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger">
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
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma disciplina cadastrada</h5>
                <p class="text-muted">Clique no botão "Nova Disciplina" para adicionar a primeira disciplina.</p>
                <a href="<?php echo e(route('disciplines.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeira Disciplina
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\PEDR0 Henrique\Documents\FACULDADE\Eletiva-II-ads-2025\resources\views/disciplines/index.blade.php ENDPATH**/ ?>