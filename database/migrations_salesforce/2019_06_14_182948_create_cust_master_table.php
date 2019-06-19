<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('cust_master__c', function(Blueprint $table)
		{
			$table->integer('cid__c', true);
			$table->string('flg__c', 2)->default('Y');
			$table->string('webmem__c', 2);
			$table->text('login_pass__c')->nullable();
			$table->integer('res_permit_cnt__c')->default(0);
			$table->string('gmo_credit__c', 30);
			$table->integer('memberid__c')->nullable();
			$table->string('memberid2__c', 64);
			$table->integer('reason_admit__c');
			$table->date('admit_date__c');
			$table->integer('admit_onday__c')->default(0);
			$table->integer('reason_rest__c');
			$table->date('rest_date__c');
			$table->integer('quit__c');
			$table->date('quit_date__c');
			$table->date('readmit_date__c');
			$table->date('resumption__c');
			$table->date('recess__c');
			$table->string('idm__c', 32)->nullable();
			$table->string('rank__c', 4)->default('Z');
			$table->integer('memtype__c')->default(0);
			$table->integer('experience_flg__c')->default(0);
			$table->integer('store_id__c')->default(0);
			$table->integer('last_tenpo__c');
			$table->string('sex__c', 4);
			$table->string('kana__c', 64);
			$table->string('name__c', 64);
			$table->string('nickname__c', 64);
			$table->text('caution__c')->nullable();
			$table->string('b_code__c', 20);
			$table->string('b_name__c', 20);
			$table->string('br_code__c', 20);
			$table->string('br_name__c', 20);
			$table->string('c_code__c', 20);
			$table->string('c_name__c', 20);
			$table->string('a_name__c', 20);
			$table->string('a_number__c', 20);
			$table->string('y_code__c', 20);
			$table->string('y_number__c', 20);
			$table->string('ya_name__c', 20);
			$table->integer('bank_type__c')->default(1);
			$table->date('transfer_date__c')->nullable();
			$table->integer('b_year__c')->nullable();
			$table->integer('b_month__c')->nullable();
			$table->integer('b_day__c')->nullable();
			$table->integer('kubun1_id__c')->default(0);
			$table->integer('kubun2_id__c')->default(0);
			$table->integer('kubun3_id__c')->default(0);
			$table->integer('kubun4_id__c');
			$table->text('kubun5__c');
			$table->text('kubun6__c');
			$table->text('kubun7__c');
			$table->text('kubun8__c');
			$table->text('hw__c')->nullable();
			$table->string('h_zip__c', 8)->nullable();
			$table->string('h_addr1__c', 128)->nullable();
			$table->string('h_addr2__c', 128)->nullable();
			$table->string('h_buil__c', 128)->nullable();
			$table->char('ng_h_hagaki__c', 1)->nullable();
			$table->string('h_tel1__c', 16)->nullable();
			$table->string('h_tel2__c', 16)->nullable();
			$table->string('h_tel3__c', 16)->nullable();
			$table->string('cellph1__c', 16)->nullable();
			$table->string('cellph2__c', 16)->nullable();
			$table->string('cellph3__c', 16)->nullable();
			$table->string('fax1__c', 16)->nullable();
			$table->string('fax2__c', 16)->nullable();
			$table->string('fax3__c', 16)->nullable();
			$table->string('c_mail__c', 128)->nullable();
			$table->integer('c_conf__c')->default(0);
			$table->integer('ng_c_mail__c')->default(0);
			$table->string('pc_mail__c', 128)->nullable();
			$table->integer('pc_conf__c')->default(0);
			$table->integer('ng_pc_mail__c')->default(0);
			$table->integer('job_id__c')->nullable();
			$table->integer('jobkind_id__c')->nullable();
			$table->text('syoujo_sub1__c')->nullable();
			$table->text('syoujo_sub2__c')->nullable();
			$table->text('t_change__c')->nullable();
			$table->text('t_comment__c')->nullable();
			$table->string('kana_g__c', 64);
			$table->string('name_g__c', 64);
			$table->string('fam_relation__c', 64);
			$table->string('h_zip_g__c', 8)->nullable();
			$table->string('h_addr1_g__c', 128)->nullable();
			$table->string('h_addr2_g__c', 128)->nullable();
			$table->string('h_buil_g__c', 128)->nullable();
			$table->char('ng_h_hagaki_g__c', 1)->nullable();
			$table->string('h_tel1_g__c', 16)->nullable();
			$table->string('h_tel2_g__c', 16)->nullable();
			$table->string('h_tel3_g__c', 16)->nullable();
			$table->string('cellph1_g__c', 16)->nullable();
			$table->string('cellph2_g__c', 16)->nullable();
			$table->string('cellph3_g__c', 16)->nullable();
			$table->string('fax1_g__c', 16)->nullable();
			$table->string('fax2_g__c', 16)->nullable();
			$table->string('fax3_g__c', 16)->nullable();
			$table->string('c_mail_g__c', 128)->nullable();
			$table->string('pc_mail_g__c', 128)->nullable();
			$table->char('memberflg__c', 1)->nullable()->default('N');
			$table->date('type_edit_date__c')->nullable();
			$table->string('dm_list__c', 128)->nullable();
			$table->text('w_report__c')->nullable();
			$table->integer('m_pub__c')->nullable()->default(0);
			$table->integer('wpatern__c');
			$table->text('week_id__c');
			$table->date('first_buy__c')->nullable();
			$table->date('first_lesson__c')->nullable();
			$table->date('last_lesson__c')->nullable();
			$table->text('eraser_name__c')->nullable();
			$table->text('reg_user__c')->nullable();
			$table->integer('edit_tenpoid__c')->nullable();
			$table->date('edit_date__c')->nullable();
			$table->text('edit_time__c')->nullable();
			$table->date('del_date__c')->nullable();
			$table->text('del_time__c')->nullable();
			$table->date('raiten__c')->nullable();
			$table->dateTime('reedit_date__c')->nullable();
			$table->integer('unpay_total__c')->nullable();
			$table->text('mov_id__c')->nullable();
			$table->text('mov_id_all__c')->nullable();
			$table->text('info_boad__c')->nullable();
			$table->string('mail_delivery__c', 2)->nullable();
			$table->integer('no_mess__c')->default(0);
			$table->char('reserve_lock__c', 1)->default('N');
			$table->text('salt__c');
			$table->dateTime('password_change_datetime__c');
			$table->integer('login_trial_count__c')->default(0);
		});
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cust_master__c');
    }
}
