<nav class="sidebar" id="sidebar">
  <ul class="nav">
      <li class="nav-item nav-profile">
          <a href="#" class="nav-link">
              <div class="profile-image">
                  <img class="img-md rounded-circle"
                  src="{{ Auth::user()->img ? asset('uploads/user/' . Auth::user()->img) : asset('back-end/assets/images/faces/face8.jpg') }}"
                  alt="Profile image" style="width: 35px; height: 35px; object-fit: center;">
                  <div class="dot-indicator bg-success"></div>
              </div>
              <div class="text-wrapper">
                  <p class="profile-name">{{ Auth::user()->name ?? 'No Name' }}</p>
                  <p class="designation">{{ Auth::user()->role == 1 ? 'Administrator' : 'Premium User' }}</p>
              </div>
          </a>
      </li>
      <li class="nav-item nav-category">Main Menu</li>

      <!-- Dashboard and User Management: visible only to Admin (row == 0) -->
      @if (Auth::user()->role == 1)
      <li class="nav-item">
          <a class="nav-link" href="{{ route('dashboard.index') }}">
              <i class="menu-icon typcn typcn-document-text"></i>
              <span class="menu-title">Dashboard</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('user.index') }}">
              <i class="menu-icon typcn typcn-document-add"></i>
              <span class="menu-title">User Management</span>
          </a>
      </li>
      @endif

      <!-- Category, Brand, Color: visible to all users -->
      <li class="nav-item">
          <a class="nav-link" href="{{ route('category.index') }}">
              <i class="menu-icon typcn typcn-document-add"></i>
              <span class="menu-title">Category</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('brand.index') }}">
              <i class="menu-icon typcn typcn-document-add"></i>
              <span class="menu-title">Brand</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('color.index') }}">
              <i class="menu-icon typcn typcn-document-add"></i>
              <span class="menu-title">Color</span>
          </a>
      </li>
  </ul>
</nav>
