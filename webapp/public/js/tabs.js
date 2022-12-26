

function showTab(){
    let hash = window.location.hash;
    if(hash == '') return;
    if(hash.charAt(0) == '#'){
        hash = hash.substring(1);
    }   
    
    const selected_tab = document.querySelector('.nav-link[hash='+hash+']')
    const selected_content = document.querySelector('.tab-pane[hash='+hash+']')

    if(selected_tab == null) return;
    if(selected_content == null) return;
    
    selected_content.classList.add('active');
    selected_content.classList.add('show');
    selected_tab.classList.add('active');
    selected_tab.setAttribute('aria-selected',"true");

    const tabs = document.querySelectorAll('.nav-link');
    for (var i = 0; i < tabs.length; i++) {
        if(tabs[i] != selected_tab){
            tabs[i].classList.remove('active');
            tabs[i].setAttribute('aria-selected',"false");
        }
    }

    const tabs_content = document.querySelectorAll('.tab-pane');
    for (var i = 0; i < tabs_content.length; i++) {
        if(tabs_content[i] != selected_content)   {
            tabs_content[i].classList.remove('active');
            tabs_content[i].classList.remove('show');
        }
    }
}

function selectTab(){
    const tabs = document.querySelectorAll('.nav-link');
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].addEventListener('click', function(){
            const hash = this.getAttribute('hash');
            window.location.hash = hash;

        });
    }
}

showTab();
selectTab();