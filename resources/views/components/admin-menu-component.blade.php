
@php
    $parentMenus = $parentMenus->sortBy('order');
    $subMenus = $subMenus->sortBy('order');
@endphp

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach ($parentMenus as $parent)
            @php
                $children = $subMenus->where('parent', $parent->id);
                $childrenCount = count($children);
            @endphp

            @if ($childrenCount > 0)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="{{ $parent->icon }} nav-icon"></i>
                        <p>
                            {{ $parent->name }}
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">{{ $childrenCount }}</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($children as $smn)
                            <li class="nav-item">
                            <a href="{{ Route::has($smn->route) ? route($smn->route) : '#' }}" class="nav-link">
                                    <i class="{{ $smn->icon }} nav-icon"></i>
                                    <p>{{ $smn->name }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ Route::has($parent->route) ? route($parent->route) : '#' }}" class="nav-link">
                        <i class="{{ $parent->icon }} nav-icon"></i>
                        <p>{{ $parent->name }}</p>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>
<!-- End Sidebar Menu -->

