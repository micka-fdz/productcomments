<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
use PrestaShop\Module\ProductComment\Repository\ProductCommentRepository;

class ProductCommentsCommentGradeModuleFrontController extends ModuleFrontController
{
    public function display()
    {
        $idProducts = Tools::getValue('id_products');
        /** @var ProductCommentRepository $productCommentRepository */

        if (!is_array($idProducts)) {
            return $this->ajaxRender(null);
        }

        $productCommentRepository = $this->context->controller->getContainer()->get('product_comment_repository');

        $productsCommentsNb = $productCommentRepository->getCommentsNumberForProducts($idProducts, Configuration::get('PRODUCT_COMMENTS_MODERATE'));
        $averageGrade = $productCommentRepository->getAverageGrades($idProducts, Configuration::get('PRODUCT_COMMENTS_MODERATE'));

        $resultFormated = [];

        foreach ($idProducts as $i => $id) {
            $resultFormated []= [
                'id_product' => $id,
                'comments_nb' => $productsCommentsNb[$id],
                'average_grade' => $averageGrade[$id]
            ];
        }

        $this->ajaxRender(json_encode([
            'products' => $resultFormated
        ]));
    }
}