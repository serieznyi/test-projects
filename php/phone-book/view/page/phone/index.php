<?php

/**
 * @var \App\View $context
 * @var array $phones
 * @var string $imagesRoot
 */

use App\Web\Util\TextUtil;

$context->vars['title'] = 'Phone';

?>

<a class="btn btn-primary" href="/phone/add">Add new phone</a>

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Photo</th>
            <th scope="col">First name</th>
            <th scope="col">Second name</th>
            <th scope="col">Phone</th>
            <th scope="col">Email</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <? /** @var \App\Dto\Phone $phone */
        foreach ($phones as $key => $phone): ?>
            <tr>
                <td><?=++$key?></td>
                <td>
                    <img src="<?= $imagesRoot . $phone->getPhoto()?>" width="50"  alt=""/>
                </td>
                <td><?=$phone->getFirstName()?></td>
                <td><?=$phone->getSecondName()?></td>
                <td title="<?=TextUtil::numberToString($phone->getPhone())?>">
                    <?=$phone->getPhone()?>
                </td>
                <td><?=$phone->getEmail()?></td>
                <td>
                    <form action="/phone/delete/<?=$phone->getId()?>" method="post">
                        <div class="btn-group">
                            <a class="btn btn-warning" href="/phone/update/<?=$phone->getId()?>">Edit</a>
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </div>
                    </form>
                </td>
            </tr>
        <? endforeach;?>
    </tbody>
</table>