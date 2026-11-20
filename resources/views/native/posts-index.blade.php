<column class="w-full h-full bg-theme-background safe-area">
    <scroll-view class="w-full h-full">
        <column class="w-full px-4 pt-4 gap-3">
            @forelse ($posts as $post)
                <column class="w-full rounded-2xl bg-theme-surface-variant p-4 gap-2">
                    <column @press="navigate('/app/posts/{{ $post['id'] }}')" class="w-full gap-1">
                        <text class="text-[18] font-bold text-theme-on-surface">{{ $post['title'] }}</text>
                        <text class="text-[13] text-theme-on-surface-variant" :maxLines="2">{{ \Illuminate\Support\Str::limit($post['body'], 120) }}</text>
                        <text class="text-[12] text-theme-on-surface-variant">{{ $post['author'] }}</text>
                    </column>
                </column>
            @empty
                <column class="w-full items-center py-12 gap-2">
                    <text class="text-[15] text-theme-on-surface-variant">No posts yet.</text>
                </column>
            @endforelse
        </column>
    </scroll-view>
    @include('native.partials.footer')
</column>
