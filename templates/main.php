<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $val): ?>
                <?=include_template('data.php', ['val' => $val]); ?>
                <li class="promo__item promo__item--boards">
                    <a class="promo__link" href="pages/all-lots.html"><?= $val ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach($ads as $key => $val) : ?>
                <?=include_template('data.php', ['val' => $val]); ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= esc($val['url']); ?>" width="350" height="260" alt="<?= esc($val['title']) ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$val['category']?></span>
                        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=esc($val['title']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount"><?= esc($val["price"]); ?></span>
                                <span class="lot__cost"><?= format_amount(esc($val["price"])); ?>
                            </div>
                            <div class="lot__timer timer">
                                12:23
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
