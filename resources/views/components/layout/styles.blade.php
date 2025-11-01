<style>
    [x-cloak] { display: none !important; }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideInRight {
        from { 
            opacity: 0; 
            transform: translateX(100%); 
        }
        to { 
            opacity: 1; 
            transform: translateX(0); 
        }
    }
    
    @keyframes slideOutRight {
        from { 
            opacity: 1; 
            transform: translateX(0); 
        }
        to { 
            opacity: 0; 
            transform: translateX(100%); 
        }
    }
    
    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.7; }
        100% { transform: scale(0.95); opacity: 1; }
    }
    
    @keyframes progress {
        from { width: 100%; }
        to { width: 0%; }
    }
    
    .animate-slide-down { animation: slideDown 0.3s ease-out; }
    .animate-pulse-ring { animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    .animate-slide-in { animation: slideInRight 0.3s ease-out; }
    .animate-slide-out { animation: slideOutRight 0.3s ease-in; }
    .animate-progress { animation: progress 5s linear forwards; }
    
    .notification-item:hover { transform: translateX(-2px); }
    .notification-item { transition: all 0.2s ease; }
    
    .scrollbar-thin::-webkit-scrollbar { width: 6px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>