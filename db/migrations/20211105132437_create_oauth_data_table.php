// /db/migrations/timestamp_create_users_table.php
    <?php
    use Phinx\Migration\AbstractMigration;
    
    class CreateOauthDataTable extends AbstractMigration
    {
        /**
         * Migrate Up.
         */
        public function up()
        {
            $users = $this->table('oauth_data');
            $users->addColumn('oauth_name', 'string')
                  ->addColumn('client_id', 'string')
                  ->addColumn('client_secret', 'string')
                  ->save();
        }
        /**
         * Migrate Down.
         */
        public function down()
        {
            $this->dropTable('oauth_data');
        }
    }