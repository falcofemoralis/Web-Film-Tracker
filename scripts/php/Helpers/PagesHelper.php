<?php

function createPagesControls($pages, $cur_page, $link)
{
    echo "<div class='pagination'>";

    $prev = $cur_page - 1;
    if ($prev > 0)
        echo "<a href='$link&page=$prev'>&laquo;</a>";

    //если это последняя страница и страниц > 10, создаем (pages-10 ... pages)
    $delta = $pages - $cur_page;
    if (($cur_page == $pages || $delta < 6) && $pages > 10) {
        echo "<a href='$link&page=1'>1</a>
                  <span>...</span>";

        for ($i = $pages - 10; $i <= $pages; $i++)
            createBtn($i, $cur_page, $link);

    //если страниц больше 10, то создаем страницы от (1 ... 10) или от (1 .. n-4 - n+4 ... pages)
    } else if ($pages > 10) {
        if ($cur_page >= 10) {
            echo "<a href='$link&page=1'>1</a>
                  <span>...</span>";
            for ($i = $cur_page - 4; $i <= $cur_page + 4; $i++)
                createBtn($i, $cur_page, $link);
        } else {
            for ($i = 1; $i <= 10; $i++)
                createBtn($i, $cur_page, $link);
        }
        echo "<span>...</span>
              <a href='$link&page=$pages'>$pages</a>";
    //иначе создаем страницы (1 ... pages)
    } else {
        for ($i = 1; $i <= $pages; $i++)
            createBtn($i, $cur_page, $link);
    }

    $next = $cur_page + 1;
    if ($next < $pages)
        echo "<a href='$link&page=$next'>&raquo;</a>";

    echo "</div>";
}

function createBtn($i, $cur_page, $link)
{
    if ($i == $cur_page) echo "<a class='active' href='$link&page=$i'>$i</a>";
    else echo "<a href='$link&page=$i'>$i</a>";
}


