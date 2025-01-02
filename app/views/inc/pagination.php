<?php

/**
 * @var Pagination $pagination
 */
?>
<?php if ($pagination->getTotalPages() > 1): ?>
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($pagination->hasPreviousPage()): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pagination->getCurrentPage() - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pagination->getTotalPages(); $i++): ?>
                        <li class="page-item <?= $i === $pagination->getCurrentPage() ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagination->hasNextPage()): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pagination->getCurrentPage() + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
<?php endif; ?>