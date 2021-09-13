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
        <h2>Результаты поиска по запросу «<span><?=htmlspecialchars('search'); ?></span>»</h2>
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
                    $arr = get_dt_range($val["dt_end"]);
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
      <ul class="pagination-list">
        <?php foreach ($pages as $page): ?>
          <li class="pagination-item <?php if($page < $cur_page): ?>pagination-item-prev<?php endif; ?>"> <a href="/?page=<?= $cur_page - 1 ;?>">Назад</a></li>
          <li class="pagination-item <?php if ($page == $cur_page): ?>pagination__item--active<?php endif; ?>"><a href="/?page=<?=$page;?>"><?=$page;?></a></li>
          <li class="pagination-item <?php if($page > $cur_page): ?>pagination-item-next<?php endif; ?>">  <a href="/?page=<?= $cur_page + 1 ;?>">Вперед</a></li>
      </ul>
      <?php endforeach; ?>
    </div>
  </main>
