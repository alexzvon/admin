@servers(['web' => 'mossebo@95.213.191.187'])

@task('deploy')
cd /srv/apps/admin.candellabra.com

php artisan down

@if ($branch)
    git pull origin {{ $branch }}
@else
    git pull origin master
@endif

@if ($composer)
    composer {{$composer}}
@else
    composer install
@endif

yarn dev

php artisan migrate --force

php artisan optimize

php artisan up

@endtask
