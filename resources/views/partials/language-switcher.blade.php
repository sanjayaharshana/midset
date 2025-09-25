<!-- Language Switcher -->
<div class="language-switcher">
    <div class="language-dropdown">
        <button class="language-btn" type="button" onclick="toggleLanguageDropdown()">
            <span class="globe-icon">🌐</span>
            <span class="current-language">
                @if(app()->getLocale() == 'si')
                    සිංහල
                @else
                    English
                @endif
            </span>
            <span class="dropdown-arrow">▼</span>
        </button>
        <div class="language-menu" id="languageMenu">
            <a class="language-option {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">
                <span class="flag">🇬🇧</span> English
            </a>
            <a class="language-option {{ app()->getLocale() == 'si' ? 'active' : '' }}" href="{{ route('language.switch', 'si') }}">
                <span class="flag">🇱🇰</span> සිංහල
            </a>
        </div>
    </div>
</div>

<style>
.language-switcher {
    display: inline-block;
    position: relative;
}

.language-dropdown {
    position: relative;
}

.language-btn {
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    min-width: 120px;
}

.language-btn:hover {
    border-color: #3b82f6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.globe-icon {
    font-size: 16px;
}

.current-language {
    font-weight: 500;
    flex: 1;
    text-align: left;
}

.dropdown-arrow {
    font-size: 10px;
    transition: transform 0.2s ease;
}

.language-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-width: 150px;
    z-index: 1000;
    display: none;
    margin-top: 4px;
}

.language-menu.show {
    display: block;
}

.language-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    text-decoration: none;
    color: #374151;
    font-size: 14px;
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
}

.language-option:last-child {
    border-bottom: none;
}

.language-option:hover {
    background-color: #f8f9fa;
}

.language-option.active {
    background-color: #e3f2fd;
    color: #1976d2;
    font-weight: 500;
}

.flag {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

/* Responsive */
@media (max-width: 768px) {
    .language-btn {
        padding: 6px 8px;
        font-size: 12px;
        min-width: 100px;
    }
    
    .language-menu {
        min-width: 120px;
    }
}
</style>

<script>
function toggleLanguageDropdown() {
    const menu = document.getElementById('languageMenu');
    const arrow = document.querySelector('.dropdown-arrow');
    
    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        arrow.style.transform = 'rotate(0deg)';
    } else {
        // Close other dropdowns
        document.querySelectorAll('.language-menu.show').forEach(m => {
            m.classList.remove('show');
            m.parentElement.querySelector('.dropdown-arrow').style.transform = 'rotate(0deg)';
        });
        
        menu.classList.add('show');
        arrow.style.transform = 'rotate(180deg)';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const switcher = document.querySelector('.language-switcher');
    if (!switcher.contains(event.target)) {
        const menu = document.getElementById('languageMenu');
        const arrow = document.querySelector('.dropdown-arrow');
        menu.classList.remove('show');
        arrow.style.transform = 'rotate(0deg)';
    }
});
</script>
