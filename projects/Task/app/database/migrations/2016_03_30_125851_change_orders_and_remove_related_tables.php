<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

use App\Models\OrderDeliveryConstants as Delivery;
use App\Models\OrderPaymentConstants as Payment;
use App\Models\OrderTypeConstants as Type;

class ChangeOrdersAndRemoveRelatedTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->dropColumn('order_delivery');
                $table->dropColumn('order_payment');
                $table->dropColumn('order_type');
                $table->dropColumn('created_hash');

                $table->unsignedInteger('product_id')->nullable();
                $table->foreign('product_id', 'orders_product_fk')->references('id')->on('catalog_products');

                $table->string('product_name');
            }
        );

        $this->getCreateProductAdditionalServicesRelationsMigration()->down();
        $this->getCreateAdditionalServicesMigration()->down();
        $this->getCreateOrderItemsTableMigration()->down();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $allowedDeliveries = [
            Delivery::OWN_EXPENSE,
            Delivery::ADDRESS,
            Delivery::COURIER,
        ];

        $allowedPayments = [
            Payment::CASH,
            Payment::CASHLESS,
            Payment::INVOICING_FOR_LEGAL_ENTITY,
            Payment::INVOICING_FOR_PHYSICAL_PERSON
        ];

        $allowedTypes = [
            Type::FROM_SITE,
            Type::FAST,
            Type::INCOMPLETE,
        ];

        Schema::table(
            'orders',
            function (Blueprint $table)  use ($allowedDeliveries, $allowedPayments, $allowedTypes) {
                $table->enum('order_delivery', $allowedDeliveries)->default($allowedDeliveries[0]);
                $table->enum('order_payment', $allowedPayments)->default($allowedPayments[0]);
                $table->enum('order_type', $allowedTypes)->default($allowedTypes[0]);
                $table->string('created_hash');

                $table->dropForeign('orders_product_fk');
                $table->dropColumn('product_id');
                $table->dropColumn('product_name');
            }
        );

        $this->getCreateOrderItemsTableMigration()->up();
        $this->getCreateAdditionalServicesMigration()->up();
        $this->getCreateProductAdditionalServicesRelationsMigration()->up();
    }

    private function getCreateOrderItemsTableMigration()
    {
        return new CreateOrderItemsTable();
    }

    private function getCreateAdditionalServicesMigration()
    {
        return new CreateAdditionalServices();
    }

    private function getCreateProductAdditionalServicesRelationsMigration()
    {
        return new CreateProductAdditionalServicesRelations();
    }
}
