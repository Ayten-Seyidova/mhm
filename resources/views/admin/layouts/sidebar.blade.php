<div class="deznav">
    <ul class="deznav-scroll">
        @auth('admin')
            <a href="{{route('customer.index')}}" class="add-menu-sidebar">Admin Panel</a>
        @endauth
        @auth('teacher')
            <a href="{{route('teacher.index')}}" class="add-menu-sidebar">Admin Panel</a>
        @endauth
        <ul class="metismenu" id="menu">
            @auth('teacher')
                <li><a href="{{ route('teacher.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="fas fa-list-alt" style="width:30px"></i>
                        <span class="nav-text">Qruplar</span>
                    </a>
                </li>
                <li><a href="{{ route('video-course.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-video-clip" style="width:30px"></i>
                        <span class="nav-text">Video kurslar</span>
                    </a>
                </li>
                <li><a href="{{ route('subject.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-list-1" style="width:30px"></i>
                        <span class="nav-text">Mövzular</span>
                    </a>
                </li>
                <li><a href="{{ route('video.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-video-player-1" style="width:30px"></i>
                        <span class="nav-text">Videolar</span>
                    </a>
                </li>
                <li><a href="{{ route('exam.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="fas fa-list-alt" style="width:30px"></i>
                        <span class="nav-text">Quizlər</span>
                    </a>
                </li>
                <li><a href="{{ route('question.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="fas fa-question" style="width:30px"></i>
                        <span class="nav-text">Suallar</span>
                    </a>
                </li>
                <li><a href="{{ route('result.index') }}" class="ai-icon" aria-expanded="false">
                        <i class="fas fa-certificate" style="width:30px"></i>
                        <span class="nav-text">Nəticələr</span>
                    </a>
                </li>
            @endauth
            @auth('admin')
                <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-users" style="width:24px"></i>
                        <span class="nav-text">İstifadəçilər</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{route('user.index')}}">Admin və müəllimlər</a></li>
                        <li><a href="{{route('group.index')}}">Qruplar</a></li>
                        <li><a href="{{route('register.index')}}">Qeydiyyat</a></li>
                        <li><a href="{{route('customer.index')}}">Tələbələr</a></li>
                        <li><a href="{{route('action.index')}}">Admin hərəkətləri</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                        <i class="flaticon-381-video-clip" style="width:24px"></i>
                        <span class="nav-text">Kurslar</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{route('video-course.index')}}">Video kurslar</a></li>
                        <li><a href="{{route('subject.index')}}">Mövzular</a></li>
                        <li><a href="{{route('video.index')}}">Videolar</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-list-alt" style="width:24px"></i>
                        <span class="nav-text">Quizlər</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{route('exam.index')}}">Quizlər</a></li>
                        <li><a href="{{route('question.index')}}">Suallar</a></li>
                        <li><a href="{{route('result.index')}}">Nəticələr</a></li>
                    </ul>
                </li>
                <li><a href="{{route("slider.index")}}" class="ai-icon" aria-expanded="false">
                        <i class="fas fa fa-sliders" style="width:30px"></i>
                        <span class="nav-text">Slayder</span>
                    </a>
                </li>
                <li><a href="{{route("faq.index")}}" class="ai-icon" aria-expanded="false">
                        <i class="fas fa fa-question-circle" style="width:30px"></i>
                        <span class="nav-text">Faq</span>
                    </a>
                </li>
                <li><a href="{{route("settings.index")}}" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-settings-2" style="width:30px"></i>
                        <span class="nav-text">Tənzimləmələr</span>
                    </a>
                </li>
                <li><a href="{{route('password')}}" class="ai-icon" aria-expanded="false">
                        <i class="flaticon-381-settings-4" style="width:30px"></i>
                        <span class="nav-text">Şifrəni dəyiş</span>
                    </a>
                </li>
            @endauth
        </ul>
        <div class="copyright">
            <p><strong>MHM</strong> © {{ date('Y') }} Bütün hüquqlar qorunur</p>
        </div>
    </ul>
</div>
