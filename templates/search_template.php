<main>
    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $val): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $val['title']; ?></a>
            </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($_GET['search']); ?></span>»</h2>
        <h3><?php if(count($results_search) === 0): ?> Ничего не найдено по Вашему запросу <?php endif; ?></h3>
        <ul class="lots__list">
          <?php foreach ($results_search as $result_search): ?>
            <li class="lots__item lot">
              <div class="lot__image">
                <img src="<?=htmlspecialchars($result_search['url_img']) ?>" width="350" height="260" alt="<?=htmlspecialchars($result_search['name']) ?>">
              </div>
              <div class="lot__info">
                <span class="lot__category"><?=htmlspecialchars($result_search['category']) ?></span>
                <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$result_search['id'] ?>"><?=htmlspecialchars($result_search['name']) ?></a></h3>
                <div class="lot__state">
                  <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?=htmlspecialchars(format_amount($result_search['start_cost'])) ?></span>
                  </div>
                  <?php
                    $arr = get_dt_range($result_search["dt_end"]);
                  ?>
                   <div class="lot__timer timer <?= $arr['hours'] === '00' ? 'timer--finishing' : ''; ?>">
                        <?= "{$arr['hours']} : {$arr['minutes']}" ?>
                   </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>

      <?php if ($pages_count > 1): ?>
      <ul class="pagination-list">
          <li class="pagination-item pagination-item-prev"><?php if($cur_page > 1): ?><a href="<?= $url . '&page=' . $prev ; ?>">Назад</a><?php endif; ?></li>
          <?php for ($i = 1; $i <= $pages_count; $i++): ?>
          <li class="pagination-item <?php if ($page === $i): ?>pagination__item--active<?php endif; ?>"><a href="<?= $url . '&page=' . $i; ?>"><?= $i; ?></a></li>
          <?php endfor; ?>
          <li class="pagination-item pagination-item-next"><?php if($cur_page < $pages_count): ?><a href="<?= $url . '&page=' . $next ; ?>">Вперед</a><?php endif; ?></li>
      </ul>
    <?php endif; ?>

    </div>
  </main>
