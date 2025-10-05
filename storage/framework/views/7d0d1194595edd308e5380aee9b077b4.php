

<?php $__env->startSection('title', 'Salas de Aula'); ?>

<?php $__env->startSection('page-title', 'Salas de Aula'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('classrooms.create')); ?>" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Nova Sala
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <?php if(count($classrooms) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Prédio</th>
                            <th>Capacidade</th>
                            <th>Tipo</th>
                            <th>Acessibilidade</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($classroom['id']); ?></td>
                            <td><code><?php echo e($classroom['code']); ?></code></td>
                            <td><?php echo e($classroom['building']); ?></td>
                            <td><strong><?php echo e($classroom['capacity']); ?></strong> lugares</td>
                            <td>
                                <span class="badge bg-<?php echo e($classroom['type'] == 'aula teorica' ? 'primary' : ($classroom['type'] == 'laboratorio' ? 'info' : 'warning')); ?>">
                                    <?php echo e(ucfirst($classroom['type'])); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($classroom['has_accessibility']): ?>
                                    <i class="fas fa-wheelchair text-success" title="Possui acessibilidade"></i>
                                <?php else: ?>
                                    <i class="fas fa-times text-muted" title="Não possui acessibilidade"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-container">
                                    <a href="<?php echo e(route('classrooms.edit', $classroom['id'])); ?>" class="btn btn-outline-primary action-btn" title="Editar Sala">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('classrooms.destroy', $classroom['id'])); ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta sala?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir Sala">
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
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma sala cadastrada</h5>
                <p class="text-muted">Clique no botão "Nova Sala" para adicionar a primeira sala.</p>
                <a href="<?php echo e(route('classrooms.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeira Sala
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\PEDR0 Henrique\Documents\FACULDADE\Eletiva-II-ads-2025\resources\views/classrooms/index.blade.php ENDPATH**/ ?>