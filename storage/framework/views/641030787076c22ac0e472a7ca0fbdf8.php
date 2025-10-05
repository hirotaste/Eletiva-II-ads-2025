

<?php $__env->startSection('title', 'Estudantes'); ?>

<?php $__env->startSection('page-title', 'Estudantes'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('students.create')); ?>" class="btn btn-primary btn-custom">
    <i class="fas fa-plus me-2"></i>Novo Estudante
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body">
        <?php if(count($students) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>GPA</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($student['id']); ?></td>
                            <td><code><?php echo e($student['registration_number']); ?></code></td>
                            <td><?php echo e($student['name']); ?></td>
                            <td><?php echo e($student['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($student['status'] == 'ativo' ? 'success' : 'warning'); ?>">
                                    <?php echo e(ucfirst($student['status'])); ?>

                                </span>
                            </td>
                            <td><strong><?php echo e($student['gpa']); ?></strong></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('students.edit', $student['id'])); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('students.destroy', $student['id'])); ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir este estudante?')">
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
                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhum estudante cadastrado</h5>
                <p class="text-muted">Clique no botão "Novo Estudante" para adicionar o primeiro estudante.</p>
                <a href="<?php echo e(route('students.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Estudante
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\PEDR0 Henrique\Documents\FACULDADE\Eletiva-II-ads-2025\resources\views/students/index.blade.php ENDPATH**/ ?>