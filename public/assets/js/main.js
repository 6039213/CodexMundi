// Utilities
const $ = (sel, ctx=document) => ctx.querySelector(sel);
const $$ = (sel, ctx=document) => Array.from(ctx.querySelectorAll(sel));

// Nav active
function initNavActive(){
	const here = location.pathname + (location.search? '':'');
	$$('a[data-nav]').forEach(a=>{
		if(here.startsWith(a.getAttribute('data-nav'))) a.classList.add('active');
	});
}

// Toasts
export function showToast(message, timeout=2500){
	let el = $('#toast');
	if(!el){
		el = document.createElement('div');
		el.id = 'toast';
		el.className = 'alert';
		document.body.appendChild(el);
	}
	el.textContent = message;
	el.classList.add('show');
	setTimeout(()=>el.classList.remove('show'), timeout);
}

// Grid/List toggle
function initViewToggle(){
	const gridBtn = $('#view-grid');
	const listBtn = $('#view-list');
	const list = $('#wonder-list');
	if(!gridBtn || !listBtn || !list) return;
	gridBtn.addEventListener('click', ()=>{
		gridBtn.classList.add('active');
		listBtn.classList.remove('active');
		list.classList.remove('list');
	});
	listBtn.addEventListener('click', ()=>{
		listBtn.classList.add('active');
		gridBtn.classList.remove('active');
		list.classList.add('list');
	});
}

// Tabs
function initTabs(){
	$$('.tabs').forEach(tabs=>{
		const buttons = $$('.tab-btn', tabs);
		const panels = $$('.tab-panel', tabs.parentElement);
		buttons.forEach(btn=>btn.addEventListener('click', ()=>{
			buttons.forEach(b=>b.classList.remove('active'));
			btn.classList.add('active');
			const target = btn.getAttribute('data-target');
			panels.forEach(p=>p.classList.toggle('active', p.id === target));
		}));
	});
}

// Lightbox (simple)
function initLightbox(){
	$$('[data-lightbox] img').forEach(img=>{
		img.style.cursor='zoom-in';
		img.addEventListener('click',()=>{
			const overlay = document.createElement('div');
			overlay.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center;z-index:200;';
			const clone = img.cloneNode();
			clone.style.maxWidth='90%';clone.style.maxHeight='90%';
			overlay.appendChild(clone);
			overlay.addEventListener('click',()=>overlay.remove());
			document.body.appendChild(overlay);
		});
	});
}

// Search and filter demo
function initSearchFilters(){
	const input = $('#search');
	const items = $$('#wonder-list .card');
	const filters = $$('.filter input');
	const sort = $('#sort');
	let t;
	function apply(){
		const q = (input?.value||'').toLowerCase();
		const selected = new Map();
		filters.forEach(f=>{ if(f.checked){ const g=f.getAttribute('data-group'); selected.set(g,(selected.get(g)||new Set()).add(f.value)); }});
		let arr = items.slice();
		arr.forEach(card=>{
			const title = card.getAttribute('data-title')||'';
			const cat = card.getAttribute('data-category')||'';
			const cont = card.getAttribute('data-continent')||'';
			const exists = card.getAttribute('data-exists')||'';
			let visible = true;
			if(q && !title.toLowerCase().includes(q)) visible=false;
			if(visible && selected.get('category') && !selected.get('category').has(cat)) visible=false;
			if(visible && selected.get('continent') && !selected.get('continent').has(cont)) visible=false;
			if(visible && selected.get('exists') && !selected.get('exists').has(exists)) visible=false;
			card.style.display = visible ? '' : 'none';
		});
		if(sort){
			const list = $('#wonder-list');
			const sorted = items.slice().sort((a,b)=>{
				const s = sort.value;
				if(s==='name') return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
				if(s==='year') return (parseInt(a.getAttribute('data-year')||'0')||0)-(parseInt(b.getAttribute('data-year')||'0')||0);
				return 0;
			});
			sorted.forEach(el=>list.appendChild(el));
		}
	}
	[input, ...filters, sort].filter(Boolean).forEach(el=>el.addEventListener('input', ()=>{ clearTimeout(t); t=setTimeout(apply, 200); }));
	apply();
}

// Init
window.addEventListener('DOMContentLoaded', ()=>{
	initNavActive();
	initViewToggle();
	initTabs();
	initLightbox();
	initSearchFilters();
	// reveal on scroll
	const obs = new IntersectionObserver((entries)=>{
		entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('show'); });
	},{threshold:0.1});
	$$('.reveal, .card').forEach(el=>obs.observe(el));
});
