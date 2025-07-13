<!-- sidebar-wrapper -->
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content" data-simplebar style="height: calc(100% - 60px);">
        <div class="sidebar-brand justify-content-center">
            <img src="{{ asset('default/logo-transparent.png') }}" height="55" alt="logo">
        </div>

        <ul class="sidebar-menu">
            <li><a href="{{ route('dashboard') }}"><i class="uil uil-estate me-2"></i>Dashboard</a></li>
            <li><a href="{{ route('foods.index') }}"><i class="uil uil-utensils me-2"></i>Makanan</a></li>
            <li><a href="{{ route('snacks.index') }}"><i class="uil uil-pizza-slice me-2"></i>Cemilan</a></li>
            <li><a href="{{ route('drinks.index') }}"><i class="uil uil-coffee me-2"></i>Minuman</a></li>
            <li><a href="{{ route('sports.index') }}"><i class="uil uil-dumbbell me-2"></i>Olahraga</a></li>
            <li><a href="{{ route('educations.index') }}"><i class="uil uil-video me-2"></i>Edukasi</a></li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="uil uil-history me-2"></i>Riwayat</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{ route('histories.bmi') }}">BMI</a></li>
                        <li><a href="{{ route('histories.bmr') }}">BMR dan TDEE</a></li>
                        <li><a href="{{ route('histories.breakfast') }}">Makanan Pagi</a></li>
                        <li><a href="{{ route('histories.lunch') }}">Makanan Siang</a></li>
                        <li><a href="{{ route('histories.dinner') }}">Makanan Malam</a></li>
                        <li><a href="{{ route('histories.snack') }}">Cemilan</a></li>
                        <li><a href="{{ route('histories.drink') }}">Air Minum</a></li>
                        <li><a href="{{ route('histories.sport') }}">Olahraga</a></li>
<li><a href="{{ route('histories.educations-history.index') }}">Edukasi</a></li>
                        <li><a href="{{ route('histories.export') }}">Export Data</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="{{ route('consultations.index') }}"><i class="uil uil-comments me-2"></i>Konsultasi</a></li>
            <li><a href="{{ route('users.index') }}"><i class="uil uil-users-alt me-2"></i>Pengguna</a></li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="uil uil-user me-2"></i>Akun</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{ route('profile.index') }}">Profil</a></li>
                        <li><a href="{{ route('change-password.index') }}">Ganti Kata Sandi</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <!-- sidebar-menu  -->
    </div>
</nav>
<!-- sidebar-wrapper  -->
