pwd = Dir.pwd

Vagrant.configure(2) do |config|
  config.vm.define 'base' do |base|
    base.vm.provider 'docker' do |d|
      d.build_dir  = 'docker/base'
      d.remains_running = false
      d.build_args = ['-t', 'pmt-base']
    end
  end

  config.vm.define 'data' do |data|
    data.vm.provider 'docker' do |d|
      d.build_dir  = 'docker/data'
      d.remains_running = false
      d.build_args = ['-t', 'pmt-data']
      d.name = 'pmt.data'
    end
  end

  config.vm.define 'mysql' do |mysql|
    mysql.vm.provider 'docker' do |d|
      d.build_dir  = 'docker/mysql'
      d.build_args = ['-t', 'pmt-mysql']
      d.create_args = ['--volumes-from=pmt.data']
      d.volumes = ["#{pwd}/docker/mysql/init.sql:/init.sql:/init.sql",
                   "#{pwd}/docker/mysql/my.cnf:/etc/mysql/my.cnf:/etc/mysql/my.cnf"]
      d.name = 'pmt.mysql'
    end
  end

  config.vm.define 'php' do |php|
    php.vm.provider 'docker' do |d|
      d.build_dir  = 'docker/php'
      d.build_args = ['-t', 'pmt-php']
      d.create_args = ['--link', 'pmt.mysql:mysql']
      d.volumes = ["#{pwd}:/var/www",
                  "#{pwd}/docker/php/php.ini:/etc/php5/fpm/php.ini",
                  "#{pwd}/docker/php/www.conf:/etc/php5/fpm/pool.d/www.conf"]
      d.name = 'pmt.php'
    end
  end

  config.vm.define 'nginx' do |nginx|
    nginx.vm.provider 'docker' do |d|
      d.build_dir  = 'docker/nginx'
      d.build_args = ['-t', 'pmt-nginx']
      d.create_args = ['--link', 'pmt.php:php']
      d.volumes = ["#{pwd}:/var/www",
                   "#{pwd}/docker/nginx/vhost.conf:/etc/nginx/sites-enabled/default",
                   "#{pwd}/docker/nginx/nginx.conf:/etc/nginx/nginx.conf"]
      d.ports = ['80:80']
      d.name = 'pmt.nginx'
    end
  end
  
end