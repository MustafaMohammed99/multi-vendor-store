<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        {{-- {{$items}} --}}
        @foreach($items as $item)
        <li class="nav-item">
            <a href="{{ route($item['route']) }}" class="nav-link {{ Route::is($item['active'])? 'active' : '' }}">
                <i class="{{ $item['icon'] }}"></i>
                <p>
                    {{ $item['title'] }}
                    @if(isset($item['badge']))
                    <span class="right badge badge-danger">{{ $item['badge'] }}</span>
                    @endif
                </p>
            </a>
        </li>
        @endforeach
    </ul>
</nav>

<!-- /.sidebar-menu -->
{{--
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach ($items as $key => $item)
            @if (strpos($key, 'parent_') !== false)
                @php
                    $nameKey = str_replace('parent_', '', $key);
                @endphp
                <li class="nav-item {{ Route::is('dashboard.'.$nameKey.'.*') ? 'nav-item menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            {{ $nameKey }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($item as $subItem)
                            <li class="nav-item">
                                <a href="{{ route($subItem['route']) }}"
                                    class="nav-link {{ Route::is($subItem['active']) ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ $subItem['title'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route($item['route']) }}"
                        class="nav-link {{ Route::is($item['active']) ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }}"></i>
                        <p>
                            {{ $item['title'] }}
                            @if (isset($item['badge']))
                                <span class="right badge badge-danger">{{ $item['badge'] }}</span>
                            @endif
                        </p>
                    </a>
                </li>
            @endif
        @endforeach
      </ul>
    --}}
