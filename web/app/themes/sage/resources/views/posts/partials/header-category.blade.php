<style>
    .htech-breadcrumb {
        display: none;
    }
</style>
<header class="">
    <div class="relative">
        <div class="relative flex w-full justify-center sm:items-center lg:pt-0">
            <img
                src="{{ $taxonomyTerm->img }}"
                alt="{{ $taxonomyTerm->name }}"
                class="max-h-[880px] w-full object-cover"
            />
            <div class="absolute top-0 left-0 h-full w-full bg-black opacity-50 bg-blend-multiply"></div>
            <div class="absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2 px-6">
                <div class="w-full text-center font-extralight text-white">
                    <h1 class="mb-0 text-2xl font-normal uppercase sm:text-5xl sm:font-extralight lg:text-8xl">
                        {{ $taxonomyTerm->name }}
                    </h1>
                    <div class="mt-3 text-xl sm:text-3xl lg:text-5xl">
                        {{ $taxonomyTerm->subtitle }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ht-container">
        <div class="paragraph-50 mt-8 mb-24 text-left font-extralight">
            {{ $taxonomyTerm->description }}
        </div>
    </div>
</header>
