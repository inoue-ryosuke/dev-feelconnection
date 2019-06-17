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
			$table->string('flg', 2)->default('Y');
			$table->string('webmem', 2);
			$table->text('login_pass')->nullable();
			$table->integer('res_permit_cnt')->default(0);
			$table->string('gmo_credit', 30);
			$table->integer('memberid')->nullable();
			$table->string('memberid2', 64);
			$table->integer('reason_admit');
			$table->date('admit_date');
			$table->integer('admit_onday')->default(0);
			$table->integer('reason_rest');
			$table->date('rest_date');
			$table->integer('quit');
			$table->date('quit_date');
			$table->date('readmit_date');
			$table->date('resumption');
			$table->date('recess');
			$table->string('idm', 32)->nullable();
			$table->string('rank', 4)->default('Z');
			$table->integer('memtype')->default(0);
			$table->integer('experience_flg')->default(0);
			$table->integer('store_id')->default(0);
			$table->integer('last_tenpo');
			$table->string('sex', 4);
			$table->string('kana', 64);
			$table->string('name', 64);
			$table->string('nickname', 64);
			$table->text('caution')->nullable();
			$table->string('b_code', 20);
			$table->string('b_name', 20);
			$table->string('br_code', 20);
			$table->string('br_name', 20);
			$table->string('c_code', 20);
			$table->string('c_name', 20);
			$table->string('a_name', 20);
			$table->string('a_number', 20);
			$table->string('y_code', 20);
			$table->string('y_number', 20);
			$table->string('ya_name', 20);
			$table->integer('bank_type')->default(1);
			$table->date('transfer_date')->nullable();
			$table->integer('b_year')->nullable();
			$table->integer('b_month')->nullable();
			$table->integer('b_day')->nullable();
			$table->integer('kubun1_id')->default(0);
			$table->integer('kubun2_id')->default(0);
			$table->integer('kubun3_id')->default(0);
			$table->integer('kubun4_id');
			$table->text('kubun5');
			$table->text('kubun6');
			$table->text('kubun7');
			$table->text('kubun8');
			$table->text('hw')->nullable();
			$table->string('h_zip', 8)->nullable();
			$table->string('h_addr1', 128)->nullable();
			$table->string('h_addr2', 128)->nullable();
			$table->string('h_buil', 128)->nullable();
			$table->char('ng_h_hagaki', 1)->nullable();
			$table->string('h_tel1', 16)->nullable();
			$table->string('h_tel2', 16)->nullable();
			$table->string('h_tel3', 16)->nullable();
			$table->string('cellph1', 16)->nullable();
			$table->string('cellph2', 16)->nullable();
			$table->string('cellph3', 16)->nullable();
			$table->string('fax1', 16)->nullable();
			$table->string('fax2', 16)->nullable();
			$table->string('fax3', 16)->nullable();
			$table->string('c_mail', 128)->nullable();
			$table->integer('c_conf')->default(0);
			$table->integer('ng_c_mail')->default(0);
			$table->string('pc_mail', 128)->nullable();
			$table->integer('pc_conf')->default(0);
			$table->integer('ng_pc_mail')->default(0);
			$table->integer('job_id')->nullable();
			$table->integer('jobkind_id')->nullable();
			$table->text('syoujo_sub1')->nullable();
			$table->text('syoujo_sub2')->nullable();
			$table->text('t_change')->nullable();
			$table->text('t_comment')->nullable();
			$table->string('kana_g', 64);
			$table->string('name_g', 64);
			$table->string('fam_relation', 64);
			$table->string('h_zip_g', 8)->nullable();
			$table->string('h_addr1_g', 128)->nullable();
			$table->string('h_addr2_g', 128)->nullable();
			$table->string('h_buil_g', 128)->nullable();
			$table->char('ng_h_hagaki_g', 1)->nullable();
			$table->string('h_tel1_g', 16)->nullable();
			$table->string('h_tel2_g', 16)->nullable();
			$table->string('h_tel3_g', 16)->nullable();
			$table->string('cellph1_g', 16)->nullable();
			$table->string('cellph2_g', 16)->nullable();
			$table->string('cellph3_g', 16)->nullable();
			$table->string('fax1_g', 16)->nullable();
			$table->string('fax2_g', 16)->nullable();
			$table->string('fax3_g', 16)->nullable();
			$table->string('c_mail_g', 128)->nullable();
			$table->string('pc_mail_g', 128)->nullable();
			$table->char('memberflg', 1)->nullable()->default('N');
			$table->date('type_edit_date')->nullable();
			$table->string('dm_list', 128)->nullable();
			$table->text('w_report')->nullable();
			$table->integer('m_pub')->nullable()->default(0);
			$table->integer('wpatern');
			$table->text('week_id');
			$table->date('first_buy')->nullable();
			$table->date('first_lesson')->nullable();
			$table->date('last_lesson')->nullable();
			$table->text('eraser_name')->nullable();
			$table->text('reg_user')->nullable();
			$table->integer('edit_tenpoid')->nullable();
			$table->date('edit_date')->nullable();
			$table->text('edit_time')->nullable();
			$table->date('del_date')->nullable();
			$table->text('del_time')->nullable();
			$table->date('raiten')->nullable();
			$table->dateTime('reedit_date')->nullable();
			$table->integer('unpay_total')->nullable();
			$table->text('mov_id')->nullable();
			$table->text('mov_id_all')->nullable();
			$table->text('info_boad')->nullable();
			$table->string('mail_delivery', 2)->nullable();
			$table->integer('no_mess')->default(0);
			$table->char('reserve_lock', 1)->default('N');
			$table->text('salt');
			$table->dateTime('password_change_datetime');
			$table->integer('login_trial_count')->default(0);
		});
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cust_master');
    }
}
