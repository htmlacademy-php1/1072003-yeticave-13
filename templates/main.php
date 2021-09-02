<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $val): ?>
                <li class="promo__item promo__item--<?= $val['symbol_code'] ?>">
                    <a class="promo__link" href="pages/all-lots.html"><?= $val['title'] ?></a>
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
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($val['url_img']); ?>" width="350" height="260" alt="<?= htmlspecialchars($val['name']) ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$val['title']?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$val['id']?>"><?=htmlspecialchars($val['name']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount"><?= htmlspecialchars($val['start_cost']); ?></span>
                                <span class="lot__cost"><?= format_amount($val['start_cost']); ?>
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
</main>
