<style>
/* =====================================================
   CUSTOM CI4 PAGINATION
===================================================== */

.pagination-wrapper {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-top: 24px;

    padding: 16px 20px;

    background: #ffffff;

    border: 1px solid #eaecf0;

    border-radius: 14px;

    box-shadow:
        0 2px 8px rgba(16, 24, 40, 0.04);
}


/* INFO */

.pagination-info {
    color: #667085;

    font-size: 14px;
}

.pagination-info strong {
    color: #344054;

    font-weight: 700;
}


/* PAGINATION */

.custom-pagination {
    display: flex;

    align-items: center;

    gap: 5px;
}


/* PAGE ITEM */

.custom-pagination .page-item {
    margin: 0;
}


/* PAGE LINK */

.custom-pagination .page-link {
    display: flex;

    align-items: center;

    justify-content: center;

    min-width: 38px;

    height: 38px;

    padding: 0 11px;

    border: 1px solid #d0d5dd;

    border-radius: 9px !important;

    background: #ffffff;

    color: #344054;

    font-size: 14px;

    font-weight: 500;

    line-height: 1;

    text-decoration: none;

    transition:
        background-color .2s ease,
        border-color .2s ease,
        color .2s ease,
        transform .2s ease,
        box-shadow .2s ease;
}


/* HOVER */

.custom-pagination
.page-item:not(.active):not(.disabled)
.page-link:hover {

    background: #f0faf7;

    border-color: #087f6b;

    color: #087f6b;

    transform: translateY(-1px);

    box-shadow:
        0 3px 8px rgba(8, 127, 107, .10);
}


/* ACTIVE */

.custom-pagination
.page-item.active
.page-link {

    background: #087f6b;

    border-color: #087f6b;

    color: #ffffff;

    font-weight: 700;

    box-shadow:
        0 4px 10px rgba(8, 127, 107, .20);
}


/* DISABLED */

.custom-pagination
.page-item.disabled
.page-link {

    background: #f9fafb;

    border-color: #eaecf0;

    color: #98a2b3;

    cursor: not-allowed;

    opacity: .8;
}


/* PREVIOUS / NEXT */

.custom-pagination .pager-arrow {

    gap: 7px;

    min-width: auto;

    padding-left: 14px;

    padding-right: 14px;

    font-weight: 600;
}


/* ARROW */

.custom-pagination
.pager-arrow span:first-child,
.custom-pagination
.pager-arrow span:last-child {

    font-size: 15px;

    line-height: 0;

    margin-top: -2px;
}


/* MOBILE */

@media (max-width: 768px) {

    .pagination-wrapper {

        flex-direction: column;

        align-items: stretch;

        padding: 15px;

    }


    .pagination-info {

        text-align: center;

    }


    .pagination-wrapper nav {

        display: flex;

        justify-content: center;

        overflow-x: auto;

        width: 100%;

        padding-bottom: 2px;

    }


    .custom-pagination {

        flex-wrap: nowrap;

    }


    .custom-pagination .page-link {

        min-width: 36px;

        height: 36px;

        padding: 0 9px;

        font-size: 13px;

    }


    .custom-pagination .pager-arrow-text {

        display: none;

    }


    .custom-pagination .pager-arrow {

        min-width: 36px;

        padding: 0;

    }

}  
</style>

<?php

if ($pager->hasPrevious()):

?>

    <li class="page-item">

        <a
            class="page-link pager-arrow"
            href="<?= $pager->getPrevious() ?>"
            aria-label="Sebelumnya">

            <span>‹</span>

            <span class="pager-arrow-text">
                Sebelumnya
            </span>

        </a>

    </li>

<?php else: ?>

    <li class="page-item disabled">

        <span class="page-link pager-arrow">

            <span>‹</span>

            <span class="pager-arrow-text">
                Sebelumnya
            </span>

        </span>

    </li>

<?php endif; ?>


<?php foreach ($pager->links() as $link): ?>

    <li
        class="page-item <?= $link['active']
            ? 'active'
            : '' ?>">

        <a
            class="page-link"
            href="<?= $link['uri'] ?>">

            <?= $link['title'] ?>

        </a>

    </li>

<?php endforeach; ?>


<?php if ($pager->hasNext()): ?>

    <li class="page-item">

        <a
            class="page-link pager-arrow"
            href="<?= $pager->getNext() ?>"
            aria-label="Selanjutnya">

            <span class="pager-arrow-text">
                Selanjutnya
            </span>

            <span>›</span>

        </a>

    </li>

<?php else: ?>

    <li class="page-item disabled">

        <span class="page-link pager-arrow">

            <span class="pager-arrow-text">
                Selanjutnya
            </span>

            <span>›</span>

        </span>

    </li>

<?php endif; ?>