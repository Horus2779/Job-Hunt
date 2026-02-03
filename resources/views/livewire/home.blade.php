<div>
    <div class="row">

        <div class="col-lg-6 col-sm-12">
            <div>
                <img src="//zunastatic-abf.kxcdn.com/images/global/adzuna_logo.svg" alt="adzuna">
            </div>
            <p>Jobs</p>
            @foreach ($adzuna as $job)

                <div class="row bg-white m-1">

                    <div class="title">{{ $job['title'] }}</div>
                    <div>{{ $job['company'] }}</div>
                    <div> {{ $job['location'] }} </div>
                    <div>{{ $job['description'] }}</div>
                    <div><a href="{{ $job['url'] }}" class="btn btn-sencondary">Candidatar</a></div>
                </div>

            @endforeach
        </div>

    </div>
</div>
