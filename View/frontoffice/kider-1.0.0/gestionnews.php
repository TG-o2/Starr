<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News — List</title>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@600&family=Lobster+Two:wght@700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  
  <link href="../kider-1.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="../kider-1.0.0/css/style.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
   <style>
    .page-header::before,
    .page-header::after {
      display: none !important;
    }
    
    .article-container {
        position: relative;
        margin-bottom: 25px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .article-container:hover {
        transform: translateY(-5px);
    }

    .article-container article {
        background: white;
        border-radius: 12px;
        padding: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .article-container:hover article {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-color: #0d6efd20;
    }

    .article-container article .article-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
        transition: transform 0.5s ease;
    }

    .article-container:hover article .article-image {
        transform: scale(1.03);
    }

    .article-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .article-header {
        margin-bottom: 12px;
    }

    .article-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .article-title a:hover {
        color: #0d6efd;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .article-date {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .article-category {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0f7ff;
        color: #0d6efd;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .article-excerpt {
        color: #495057;
        line-height: 1.6;
        margin-bottom: 15px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ai-summary-container {
        margin: 15px 0;
        padding: 15px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        border-left: 4px solid #0d6efd;
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .ai-summary-container.show {
        display: block;
    }

    .ai-summary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .ai-summary-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ai-summary-title i {
        color: #0d6efd;
    }

    .ai-summary-content {
        font-size: 0.875rem;
        line-height: 1.6;
        color: #495057;
        margin: 0;
    }

    .ai-summary-content.simple {
        font-size: 0.85rem;
    }

    .ai-summary-content.simple .fa-info-circle {
        color: #0d6efd;
    }

    .ai-summary-loading {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .ai-summary-error {
        color: #dc3545;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ai-summary-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .ai-summary-btn {
        padding: 5px 12px;
        font-size: 0.75rem;
        border-radius: 20px;
        border: 1px solid #dee2e6;
        background: white;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ai-summary-btn:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .ai-summary-btn.copy-btn i {
        transition: all 0.3s ease;
    }

    .ai-summary-btn.copy-btn.copied {
        background: #198754;
        border-color: #198754;
        color: white;
    }

    .ai-summary-btn.copy-btn.copied i {
        transform: scale(1.2);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .ai-generate-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        animation: fadeInModal 0.3s ease;
    }

    .ai-generate-modal.show {
        display: flex;
    }

    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .ai-generate-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .ai-generate-header {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ai-generate-header h5 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ai-generate-body {
        padding: 20px;
        max-height: 50vh;
        overflow-y: auto;
    }

    .ai-generate-preview {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #0d6efd;
    }

    .ai-generate-preview h6 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .ai-generate-preview p {
        color: #495057;
        font-size: 0.875rem;
        line-height: 1.6;
        margin: 0;
    }

    .ai-generate-footer {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8f9fa;
    }

    .ai-generate-footer .btn {
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.875rem;
    }

    .article-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
        margin-top: auto;
        gap: 10px;
        flex-wrap: wrap;
    }

    .comments-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        padding: 6px 12px;
        border-radius: 20px;
        background: #f8f9fa;
        margin-right: auto;
    }

    .comments-toggle:hover {
        background: #e9ecef;
        color: #0d6efd;
        text-decoration: none;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: rgba(255, 255, 255, 0.9);
        color: #6c757d;
        transition: all 0.2s ease;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .action-btn.edit-btn {
        background: rgba(255, 193, 7, 0.9);
        color: white;
    }

    .action-btn.delete-btn {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }

    .action-btn.ai-summary-btn {
        background: rgba(25, 135, 84, 0.9);
        color: white;
    }

    .action-btn.edit-btn:hover {
        background: #ffc107;
    }

    .action-btn.delete-btn:hover {
        background: #dc3545;
    }

    .action-btn.ai-summary-btn:hover {
        background: #198754;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        width: 100%;
    }

    .article-item {
        width: 100%;
        grid-column: span 1;
    }

    @media (max-width: 768px) {
        .articles-grid {
            grid-template-columns: 1fr;
        }
        
        .article-container article {
            flex-direction: column;
        }
        
        .article-container article .article-image {
            width: 100%;
            height: 180px;
        }
        
        .action-buttons {
            top: 10px;
            right: 10px;
        }
        
        .article-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        
        .comments-toggle {
            margin-right: 0;
            justify-content: center;
        }
        
        .article-footer .fb-reaction-container {
            justify-content: center;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .article-container {
        animation: fadeInUp 0.5s ease forwards;
    }

    .no-articles {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        grid-column: 1 / -1;
    }

    .no-articles i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .notification-bell {
      position: relative;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #6c757d;
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 50%;
      transition: all 0.3s ease;
      margin-left: 15px;
    }

    .notification-bell:hover {
      background: #f8f9fa;
      color: #0d6efd;
    }

    .notification-bell.has-notifications {
      color: #0d6efd;
    }

    .notification-bell.has-notifications::after {
      content: '';
      position: absolute;
      top: 8px;
      right: 8px;
      width: 8px;
      height: 8px;
      background: #dc3545;
      border-radius: 50%;
      border: 2px solid white;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    .notifications-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      width: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      border: 1px solid #e9ecef;
      z-index: 1000;
      display: none;
      margin-top: 10px;
    }

    .notifications-dropdown.show {
      display: block;
      animation: fadeInUp 0.3s ease;
    }

    .notifications-header {
      padding: 15px 20px;
      border-bottom: 1px solid #e9ecef;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .notifications-header h5 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }

    .notifications-list {
      max-height: 400px;
      overflow-y: auto;
    }

    .notification-item {
      padding: 15px 20px;
      border-bottom: 1px solid #f8f9fa;
      cursor: pointer;
      transition: background 0.2s ease;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    .notification-item:hover {
      background: #f8f9fa;
    }

    .notification-item:last-child {
      border-bottom: none;
    }

    .notification-item.unread {
      background: #f0f7ff;
    }

    .notification-icon {
      width: 36px;
      height: 36px;
      background: #0d6efd;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 14px;
      flex-shrink: 0;
    }

    .notification-icon.new-article {
      background: #28a745;
    }

    .notification-icon.update {
      background: #ffc107;
    }

    .notification-icon.reaction {
      background: #e83e8c;
    }

    .notification-icon.ai {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
    }

    .notification-content {
      flex: 1;
    }

    .notification-content h6 {
      margin: 0 0 4px 0;
      font-size: 14px;
      font-weight: 600;
      color: #2c3e50;
    }

    .notification-content p {
      margin: 0;
      font-size: 13px;
      color: #6c757d;
      line-height: 1.4;
    }

    .notification-time {
      font-size: 11px;
      color: #adb5bd;
      margin-top: 4px;
    }

    .notifications-footer {
      padding: 12px 20px;
      border-top: 1px solid #e9ecef;
      text-align: center;
    }

    .notifications-footer a {
      color: #0d6efd;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
    }

    .notifications-footer a:hover {
      text-decoration: underline;
    }

    .empty-notifications {
      padding: 40px 20px;
      text-align: center;
      color: #6c757d;
    }

    .empty-notifications i {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #dee2e6;
    }

    .notification-badge {
      position: absolute;
      top: 5px;
      right: 5px;
      background: #dc3545;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    @keyframes shake {
      0%, 100% { transform: rotate(0); }
      25% { transform: rotate(-15deg); }
      75% { transform: rotate(15deg); }
    }

    .is-valid {
      border-color: #198754 !important;
    }

    .is-invalid {
      border-color: #dc3545 !important;
    }

    .error-msg {
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 0.25rem;
    }

    .article-footer .fb-reaction-container {
        position: relative;
        display: inline-flex;
        align-items: center;
        margin-left: auto;
        z-index: 10;
    }

    .modern-comment-actions .fb-reaction-container {
        position: relative;
        display: inline-flex;
        align-items: center;
        z-index: 100;
    }

    .fb-like-btn {
        background: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 18px !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #65676b !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        cursor: pointer !important;
        white-space: nowrap;
        position: relative;
        z-index: 5;
    }

    .fb-like-btn:hover {
        background: #e9ecef !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
    }

    .article-footer .fb-reaction-panel {
        position: absolute;
        bottom: 100%;
        right: 0;
        background: white;
        border-radius: 28px;
        padding: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: 1px solid #e4e6ea;
        display: none;
        gap: 4px;
        z-index: 1000;
        margin-bottom: 10px;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        transform: scale(0.8);
        opacity: 0;
    }

    .modern-comment-actions .fb-reaction-panel {
        position: absolute;
        bottom: 100%;
        left: 0;
        background: white;
        border-radius: 28px;
        padding: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: 1px solid #e4e6ea;
        display: none;
        gap: 4px;
        z-index: 1000;
        margin-bottom: 15px;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        transform: scale(0.8);
        opacity: 0;
    }

    .article-footer .fb-reaction-panel.show,
    .modern-comment-actions .fb-reaction-panel.show {
        display: flex;
        opacity: 1;
        transform: scale(1);
    }

    .fb-reaction-btn {
        background: none !important;
        border: none !important;
        font-size: 24px !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        transform: scale(1);
        border-radius: 50%;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }

    .fb-reaction-btn:hover {
        transform: scale(1.4) translateY(-8px) !important;
    }

    .fb-reaction-btn.fb-like { color: #1877f2; }
    .fb-reaction-btn.fb-love { color: #f33e58; }
    .fb-reaction-btn.fb-haha { color: #f7b125; }
    .fb-reaction-btn.fb-wow { color: #f7b125; }
    .fb-reaction-btn.fb-sad { color: #f7b125; }
    .fb-reaction-btn.fb-angry { color: #e4715a; }

    .modern-comment-actions {
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
        position: relative;
        padding-right: 10px;
    }

    .modern-comment-item, 
    .modern-comment-reply-item {
        position: relative;
        overflow: visible !important;
    }

    .modern-comment-actions .btn-action {
        background: none;
        border: none;
        color: #6c757d;
        font-size: 14px;
        padding: 6px 0;
        cursor: pointer;
        transition: color 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 1;
    }

    @media (max-width: 768px) {
        .article-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        
        .article-footer .comments-toggle {
            margin-right: 0;
            justify-content: center;
        }
        
        .article-footer .fb-reaction-container {
            margin-left: 0;
            margin-top: 10px;
            justify-content: center;
        }
        
        .article-footer .fb-reaction-panel {
            left: 50%;
            transform: translateX(-50%) scale(0.8);
        }
        
        .article-footer .fb-reaction-panel.show {
            transform: translateX(-50%) scale(1);
        }
        
        .modern-comment-actions .fb-reaction-panel {
            left: 0;
            transform: scale(0.8);
        }
        
        .modern-comment-actions .fb-reaction-panel.show {
            transform: scale(1);
            left: 0;
        }
        
        .modern-comment-actions {
            flex-wrap: wrap;
            gap: 8px;
        }
    }

    .fb-like-btn span {
        white-space: nowrap;
    }

    .fb-reaction-panel {
        z-index: 1000 !important;
    }

    .comments-modern-container {
      background: #ffffff;
      border-radius: 12px;
      border: 1px solid #e9ecef;
      margin-top: 1.5rem;
      overflow: hidden;
    }

    .comments-modern-header {
      padding: 20px 24px;
      border-bottom: 1px solid #e9ecef;
      background: #f8f9fa;
    }

    .comments-modern-header h5 {
      margin: 0;
      font-size: 18px;
      font-weight: 600;
      color: #2c3e50;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .comments-modern-header h5 i {
      color: #0d6efd;
    }

    .comments-modern-list {
      padding: 0;
      max-height: 400px;
      overflow-y: auto;
    }

    .modern-comment-item {
      padding: 24px;
      border-bottom: 1px solid #f8f9fa;
      transition: background 0.2s ease;
    }

    .modern-comment-item:hover {
      background: #f8f9fa;
    }

    .modern-comment-item:last-child {
      border-bottom: none;
    }

    .modern-comment-author {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
    }

    .modern-comment-author .author-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 16px;
      margin-right: 12px;
    }

    .modern-comment-author .author-info {
      flex: 1;
    }

    .modern-comment-author .author-name {
      font-weight: 600;
      color: #2c3e50;
      margin: 0 0 4px 0;
      font-size: 15px;
    }

    .modern-comment-author .comment-time {
      color: #6c757d;
      font-size: 13px;
      margin: 0;
    }

    .modern-comment-content {
      margin-left: 52px;
      color: #495057;
      line-height: 1.6;
      font-size: 15px;
      margin-bottom: 16px;
    }

    .modern-comment-actions {
      margin-left: 52px;
      display: flex;
      gap: 16px;
      align-items: center;
      flex-wrap: wrap;
    }

    .modern-comment-actions .btn-action {
      background: none;
      border: none;
      color: #6c757d;
      font-size: 14px;
      padding: 6px 0;
      cursor: pointer;
      transition: color 0.2s ease;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .modern-comment-actions .btn-action:hover {
      color: #0d6efd;
    }

    .modern-comment-actions .btn-action i {
      font-size: 12px;
    }

    .modern-comment-reply-form {
      margin-left: 52px;
      margin-top: 16px;
      padding-top: 16px;
      border-top: 1px solid #e9ecef;
      display: none;
    }

    .modern-comment-reply-form.show {
      display: block;
      animation: fadeInUp 0.3s ease;
    }

    .modern-comment-reply-form .input-group {
      margin-bottom: 8px;
    }

    .modern-comment-reply-form .reply-input {
      border-radius: 20px;
      padding: 10px 20px;
      border: 1px solid #dee2e6;
      font-size: 14px;
    }

    .modern-comment-reply-form .reply-input:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .modern-comment-reply-buttons {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
    }

    .modern-comment-reply-buttons .btn {
      padding: 6px 20px;
      font-size: 14px;
      border-radius: 20px;
    }

    .modern-comment-replies {
      margin-left: 52px;
      margin-top: 20px;
      border-left: 2px solid #e9ecef;
      padding-left: 20px;
    }

    .modern-comment-reply-item {
      margin-bottom: 20px;
      position: relative;
    }

    .modern-comment-reply-item:last-child {
      margin-bottom: 0;
    }

    .modern-comment-reply-item::before {
      content: '';
      position: absolute;
      left: -21px;
      top: 20px;
      width: 8px;
      height: 8px;
      background: #0d6efd;
      border-radius: 50%;
    }

    .add-comment-container {
      padding: 20px 24px;
      border-top: 1px solid #e9ecef;
      background: #f8f9fa;
    }

    .add-comment-form .comment-input {
      border-radius: 20px;
      padding: 12px 24px;
      border: 1px solid #dee2e6;
      font-size: 15px;
      transition: all 0.2s ease;
    }

    .add-comment-form .comment-input:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
      outline: none;
    }

    .add-comment-form .input-group-text {
      background: #0d6efd;
      border: none;
      border-radius: 20px;
      padding: 0 24px;
      color: white;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .add-comment-form .input-group-text:hover {
      background: #0b5ed7;
    }

    .no-comments-message {
      padding: 40px 24px;
      text-align: center;
      color: #6c757d;
    }

    .no-comments-message i {
      font-size: 2.5rem;
      margin-bottom: 16px;
      color: #dee2e6;
    }

    .no-comments-message h6 {
      font-size: 16px;
      margin-bottom: 8px;
      color: #6c757d;
    }

    .view-comments-toggle {
      display: block;
      width: 100%;
      padding: 16px;
      text-align: center;
      background: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 8px;
      color: #0d6efd;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s ease;
      margin-top: 16px;
    }

    .view-comments-toggle:hover {
      background: #e9ecef;
      color: #0b5ed7;
      text-decoration: none;
    }

    .view-comments-toggle i {
      margin-right: 8px;
      transition: transform 0.2s ease;
    }

    .view-comments-toggle.collapsed i {
      transform: rotate(0deg);
    }

    .view-comments-toggle:not(.collapsed) i {
      transform: rotate(180deg);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .comments-modern-container {
      animation: fadeIn 0.3s ease;
    }
    
    .category-filter {
      transition: all 0.2s ease;
      color: #555 !important;
    }
    .category-filter:hover {
      background: #f0f7ff !important;
      color: #0d6efd !important;
    }
    .category-filter.active {
      background: #0d6efd !important;
      color: white !important;
      font-weight: 600;
    }
    
    .comments-section {
        margin-top: 15px;
        width: 100%;
    }
    
    #search-no-result, #cat-no-result {
      grid-column: 1 / -1;
    }

    .ai-controls-sidebar {
        position: fixed;
        top: 100px;
        right: 20px;
        width: 300px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 20px;
        z-index: 1000;
        display: none;
    }

    .ai-controls-sidebar.show {
        display: block;
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .ai-controls-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }

    .ai-controls-header h6 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #2c3e50;
    }

    .ai-model-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 0.875rem;
        margin-bottom: 15px;
    }

    .ai-model-select:focus {
        border-color: #0d6efd;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .ai-summary-length {
        margin-bottom: 15px;
    }

    .ai-summary-length label {
        display: block;
        margin-bottom: 8px;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .length-options {
        display: flex;
        gap: 10px;
    }

    .length-option {
        flex: 1;
        text-align: center;
        padding: 6px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .length-option:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
    }

    .length-option.active {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .ai-summary-stats {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 15px;
    }

    .ai-summary-stats p {
        margin: 5px 0;
    }
    
    /* FIX: Facebook Reaction Hover Animation */
    .fb-reaction-container {
        position: relative;
    }
    
    .fb-like-btn:hover + .fb-reaction-panel,
    .fb-reaction-panel:hover {
        display: flex !important;
        opacity: 1 !important;
        transform: scale(1) !important;
    }
    
    /* Smooth hover animation for reaction panel */
    .fb-reaction-panel {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        animation: none !important;
    }
    
    /* Form Validation Styles */
    .form-control:valid {
        border-color: #198754 !important;
    }
    
    .form-control:invalid {
        border-color: #dc3545 !important;
    }
    
    .was-validated .form-control:invalid {
        border-color: #dc3545 !important;
    }
    
    .was-validated .form-control:valid {
        border-color: #198754 !important;
    }
    
    /* Better positioning for reaction panels */
    .article-footer .fb-reaction-panel {
        position: absolute;
        bottom: 100% !important;
        right: 0 !important;
        margin-bottom: 10px !important;
    }
    
    .modern-comment-actions .fb-reaction-panel {
        position: absolute !important;
        bottom: 100% !important;
        left: 0 !important;
        margin-bottom: 10px !important;
    }
  </style>
</head>
<body>
  <div class="container-xxl bg-white p-0">
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
      <h1 class="m-0 text-primary">
        <img src="../kider-1.0.0/img/starr.jpg" alt="Starr Logo" style="height: 45px; vertical-align: middle; margin-right: 8px;">
        Starr
      </h1>
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto">
          <a href="../kider-1.0.0/index.php" class="nav-item nav-link">Home</a>
          <a href="../kider-1.0.0/gestionnews.php" class="nav-item nav-link active">News</a>
          <a href="../kider-1.0.0/classes.html" class="nav-item nav-link">Classes</a>
          <a href="../kider-1.0.0/contact.html" class="nav-item nav-link">Contact</a>
        </div>
        <div class="d-flex align-items-center">
          <!-- AI Summarize Button -->
          <button class="btn btn-success btn-sm me-2" id="aiSummarizeBtn">
            <i class="fas fa-robot"></i> AI Summarize
          </button>
          
          <!-- Notification Bell -->
          <div class="notification-wrapper position-relative">
            <button class="notification-bell" id="notificationBell">
              <i class="fas fa-bell"></i>
            </button>
            <div class="notifications-dropdown" id="notificationsDropdown">
              <div class="notifications-header">
                <h5>Notifications</h5>
                <button class="btn btn-sm btn-outline-secondary" id="markAllRead">
                  <small>Tout marquer comme lu</small>
                </button>
              </div>
              <div class="notifications-list" id="notificationsList">
                <!-- Notifications will be loaded here -->
              </div>
              <div class="notifications-footer">
                <a href="#" id="viewAllNotifications">Voir toutes les notifications</a>
              </div>
            </div>
          </div>
          <a href="#" class="btn btn-primary rounded-pill px-3 d-none d-lg-block">Join Us<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
      </div>
    </nav>

    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
      <div class="owl-carousel header-carousel position-relative">
        <div class="owl-carousel-item position-relative">
          <img class="img-fluid" src="../kider-1.0.0/img/carousel-1.jpg" alt="" style="width: 100%; height: 500px; object-fit: cover;">
          <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(0, 0, 0, .2);">
            <div class="container">
              <div class="row justify-content-start">
                <div class="col-10 col-lg-8">
                  <h1 class="display-2 text-dark animated slideInDown mb-4">Latest News & Updates</h1>
                  <p class="fs-5 fw-medium text-dark mb-4 pb-2">Stay informed with the latest articles, events, and community updates from our school.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- AI Controls Sidebar -->
    <div class="ai-controls-sidebar" id="aiControlsSidebar">
      <div class="ai-controls-header">
        <h6><i class="fas fa-robot"></i> AI Settings</h6>
        <button type="button" class="btn-close" id="closeAIControls"></button>
      </div>
      
      <div class="mb-3">
        <label for="aiModelSelect" class="form-label">AI Model</label>
        <select class="ai-model-select" id="aiModelSelect">
          <option value="local-extractive">Local Extractive Summarizer</option>
          <option value="local-tfidf">Local TF-IDF Summarizer</option>
          <option value="simple">Simple Algorithm</option>
        </select>
        <small class="text-muted">Choose the AI model for summarization</small>
      </div>
      
      <div class="ai-summary-length">
        <label>Summary Length</label>
        <div class="length-options">
          <div class="length-option active" data-length="short">Short</div>
          <div class="length-option" data-length="medium">Medium</div>
          <div class="length-option" data-length="long">Long</div>
        </div>
      </div>
      
      <button class="btn btn-primary w-100 mb-3" id="generateAllSummaries">
        <i class="fas fa-bolt"></i> Generate All Summaries
      </button>
      
      <div class="ai-summary-stats">
        <p><i class="fas fa-history"></i> API Calls Today: <span id="apiCallsCount">0</span></p>
        <p><i class="fas fa-brain"></i> Tokens Used: <span id="tokensUsed">0</span></p>
      </div>
    </div>

    <main class="container">
      <?php
      error_reporting(E_ALL);
      ini_set('display_errors', 1);
      
      require_once __DIR__ . '/../../../Controller/Config.php';
      require_once __DIR__ . '/../../../Controller/newsController.php';
      require_once __DIR__ . '/../../../Controller/commentController.php';
      
      $newsController = new NewsController();
      
      // Handle article deletion
      if (isset($_GET['delete_id'])) {
        $result = $newsController->deleteNews($_GET['delete_id']);
        if ($result) {
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Article deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
          echo '<script>setTimeout(function(){ window.location.href = "gestionnews.php"; }, 1000);</script>';
        } else {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting article!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
      }
      
      // Handle article update
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
        $update_id = $_POST['update_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $image = $_POST['current_image'];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
          $uploadDir = __DIR__ . '/../../../uploads/news/';
          if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
          $imageName = time() . '_' . basename($_FILES['image']['name']);
          $imagePath = $uploadDir . $imageName;
          if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) { $image = $imageName; }
        }
        
        if (empty($image)) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Please choose an image before updating this article.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
          $result = $newsController->updateNews($update_id, $title, $content, $category, $image);
          if ($result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Article updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            echo '<script>setTimeout(function(){ window.location.href = "gestionnews.php"; }, 1000);</script>';
          } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating article!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
          }
        }
      }
      
      // Handle new article creation
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && !isset($_POST['update_id'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $image = null;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
          $uploadDir = __DIR__ . '/../../../uploads/news/';
          if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
          $imageName = time() . '_' . basename($_FILES['image']['name']);
          $imagePath = $uploadDir . $imageName;
          if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) { $image = $imageName; }
        }
        
        if (!$image) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Please choose an image to add an article.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
          $news = new News();
          $news->setTitle($title);
          $news->setContent($content);
          $now = date('Y-m-d H:i:s');
          $news->setPublished_date($now);
          $news->setUpdated_date($now);
          $news->setImage($image);
          $news->setStatus('published');
          $news->setTeacherid(1);
          $news->setCategory($category);
          
          $result = $newsController->addNews($news);
          if ($result) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Article added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            echo '<script>setTimeout(function(){ window.location.href = "gestionnews.php"; }, 1000);</script>';
          } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding article!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
          }
        }
      }
      
      $allNews = $newsController->getAllNews();
      $editNews = isset($_GET['edit_id']) ? $newsController->getNewsById($_GET['edit_id']) : null;
      ?>

      <!-- Flash messages for comment operations -->
      <?php if (isset($_GET['comment_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          Comment posted successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (isset($_GET['reply_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          Reply posted successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (isset($_GET['comment_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          Unable to process your comment. Please try again.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="row g-4">
        <div class="col-lg-8">
          <h2 class="section-title mb-5"><span>Latest</span> News</h2>
          <div class="articles-grid">
            <?php if (!empty($allNews)): ?>
              <?php foreach ($allNews as $news): ?>
                <?php
                $commentController = new CommentController();
                $comments = $commentController->getCommentsByNewsId($news['newsid']);
                $comments_count = count($comments);
                ?>
                
                <!-- Article Container -->
                <div class="article-item">
                  <div class="article-container">
                    <article>
                      <!-- Article Image -->
                      <?php if (!empty($news['image'])): ?>
                        <img class="article-image" src="../../../uploads/news/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                      <?php else: ?>
                        <img class="article-image" src="../kider-1.0.0/img/carousel-1.jpg" alt="Default news image">
                      <?php endif; ?>
                      
                      <!-- Action Buttons -->
                      <div class="action-buttons">
                        <button class="action-btn ai-summary-btn" title="Generate AI Summary" data-article-id="<?php echo $news['newsid']; ?>" data-article-title="<?php echo htmlspecialchars($news['title']); ?>" data-article-content="<?php echo htmlspecialchars($news['content']); ?>">
                          <i class="fas fa-robot"></i>
                        </button>
                        <a href="gestionnews.php?edit_id=<?php echo $news['newsid']; ?>" class="action-btn edit-btn" title="Edit Article">
                          <i class="fas fa-edit"></i>
                        </a>
                        <a href="gestionnews.php?delete_id=<?php echo $news['newsid']; ?>" class="action-btn delete-btn" title="Delete Article" onclick="return confirm('Delete this article?')">
                          <i class="fas fa-trash"></i>
                        </a>
                      </div>
                      
                      <div class="article-content">
                        <!-- Article Header -->
                        <div class="article-header">
                          <h3 class="article-title">
                             <a class="text-decoration-none" href="article details.php?id=<?php echo $news['newsid']; ?>">
                              <?php echo htmlspecialchars($news['title']); ?>
                            </a>
                          </h3>
                          
                          <!-- Article Meta -->
                          <div class="article-meta">
                            <div class="article-date">
                              <i class="far fa-calendar"></i>
                              <?php $date = new DateTime($news['published_date']); echo $date->format('M j, Y'); ?>
                            </div>
                            <?php if (!empty($news['category'])): ?>
                              <span class="article-category">
                                <i class="fas fa-tag"></i>
                                <?php echo htmlspecialchars(ucfirst($news['category'])); ?>
                              </span>
                            <?php endif; ?>
                          </div>
                        </div>
                        
                        <!-- AI Summary Container -->
                        <div class="ai-summary-container" id="ai-summary-<?php echo $news['newsid']; ?>">
                          <div class="ai-summary-header">
                            <div class="ai-summary-title">
                              <i class="fas fa-robot"></i>
                              <span>AI Summary</span>
                            </div>
                            <div class="ai-summary-actions">
                              <button class="ai-summary-btn copy-btn" data-target="ai-summary-<?php echo $news['newsid']; ?>">
                                <i class="fas fa-copy"></i> Copy
                              </button>
                              <button class="ai-summary-btn regenerate-btn" data-article-id="<?php echo $news['newsid']; ?>">
                                <i class="fas fa-sync-alt"></i> Regenerate
                              </button>
                            </div>
                          </div>
                          <p class="ai-summary-content" id="ai-summary-content-<?php echo $news['newsid']; ?>"></p>
                        </div>
                        
                        <!-- Article Excerpt -->
                        <p class="article-excerpt">
                          <?php 
                          $content = strip_tags($news['content']);
                          echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                          ?>
                        </p>
                        
                        <!-- Article Footer -->
                        <div class="article-footer">
                          <!-- Comments Toggle -->
                          <a href="#comments-<?php echo $news['newsid']; ?>" 
                             class="comments-toggle" 
                             data-bs-toggle="collapse">
                            <i class="far fa-comment"></i>
                            <?php echo $comments_count; ?> Comments
                          </a>
                          
                          <!-- AI Summary Toggle -->
                          <button class="btn btn-sm btn-outline-primary ai-summary-toggle" data-article-id="<?php echo $news['newsid']; ?>">
                            <i class="fas fa-robot"></i> Show AI Summary
                          </button>

                          <!-- Facebook Reaction Button Container -->
                          <div class="fb-reaction-container" data-article-id="<?php echo $news['newsid']; ?>">
                            <!-- Reaction button will be added by JavaScript -->
                          </div>
                        </div>
                      </div>
                    </article>
                  </div>
                  
                  <!-- Modern Comments Section -->
                  <div class="comments-section" data-article-id="<?php echo $news['newsid']; ?>">
                    <!-- View Comments Toggle Button -->
                    <a href="#comments-<?php echo $news['newsid']; ?>" class="view-comments-toggle collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="comments-<?php echo $news['newsid']; ?>">
                      <i class="fas fa-chevron-down"></i>
                      View comments (<?php echo $comments_count; ?>)
                    </a>
                    
                    <!-- Comments Container -->
                    <div class="comments-modern-container collapse" id="comments-<?php echo $news['newsid']; ?>">
                      <!-- Comments Header -->
                      <div class="comments-modern-header">
                        <h5><i class="fas fa-comments"></i> Comments (<?php echo $comments_count; ?>)</h5>
                      </div>
                      
                      <!-- Comment List -->
                      <div class="comments-modern-list">
                        <?php if ($comments_count === 0): ?>
                          <!-- No Comments Message -->
                          <div class="no-comments-message">
                            <i class="fas fa-comment-slash"></i>
                            <h6>No comments yet</h6>
                            <p>Be the first to comment!</p>
                          </div>
                        <?php else: ?>
                          <?php
                          // Group replies by parent comment
                          $parents = [];
                          $replies = [];
                          foreach ($comments as $c) {
                            if (strpos($c['content'], 'REPLY_TO:') === 0) {
                              $pipePos = strpos($c['content'], '|');
                              $parentId = 0;
                              $replyText = $c['content'];
                              if ($pipePos !== false) {
                                $meta = substr($c['content'], 0, $pipePos);
                                $replyText = substr($c['content'], $pipePos + 1);
                                $parts = explode(':', $meta);
                                if (count($parts) === 2) { $parentId = (int)$parts[1]; }
                              }
                              $c['content'] = $replyText;
                              $replies[$parentId][] = $c;
                            } else {
                              $parents[] = $c;
                            }
                          }
                          ?>
                          
                          <?php foreach ($parents as $comment): ?>
                            <!-- Single Comment -->
                            <div class="modern-comment-item" data-comment-id="<?php echo $comment['id']; ?>">
                              <!-- Comment Author -->
                              <div class="modern-comment-author">
                                <div class="author-avatar">
                                  <?php echo substr(htmlspecialchars('Anonymous'), 0, 1); ?>
                                </div>
                                <div class="author-info">
                                  <h6 class="author-name">Anonymous</h6>
                                  <p class="comment-time">
                                    <?php 
                                    $cd = new DateTime($comment['created_at']);
                                    echo $cd->format('M j, Y');
                                    ?>
                                  </p>
                                </div>
                              </div>
                              
                              <!-- Comment Content -->
                              <div class="modern-comment-content">
                                <?php echo htmlspecialchars($comment['content']); ?>
                              </div>
                              
                              <!-- Comment Actions -->
                              <div class="modern-comment-actions">
                                <!-- Facebook Reaction Button Container -->
                                <div class="fb-reaction-container" data-comment-id="<?php echo $comment['id']; ?>">
                                  <!-- Reaction button will be added by JavaScript -->
                                </div>
                                
                                <button class="btn-action btn-reply" data-comment-id="<?php echo $comment['id']; ?>">
                                  <i class="fas fa-reply"></i> Reply
                                </button>
                                <button class="btn-action btn-edit" data-comment-id="<?php echo $comment['id']; ?>" data-comment-text="<?php echo htmlspecialchars($comment['content']); ?>">
                                  <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-action btn-delete" data-comment-id="<?php echo $comment['id']; ?>">
                                  <i class="fas fa-trash"></i> Delete
                                </button>
                              </div>
                              
                              <!-- Reply Form (hidden by default) -->
                              <div class="modern-comment-reply-form" id="reply-form-<?php echo $comment['id']; ?>">
                                <form method="POST" action="addcomments.php" class="reply-form">
                                  <input type="hidden" name="news_id" value="<?php echo $news['newsid']; ?>">
                                  <input type="hidden" name="reply_to" value="<?php echo $comment['id']; ?>">
                                  <div class="input-group">
                                    <input type="text" class="form-control reply-input" name="comment_content" placeholder="Write a reply..." required>
                                  </div>
                                  <div class="modern-comment-reply-buttons mt-2">
                                    <button type="button" class="btn btn-secondary cancel-reply">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Reply</button>
                                  </div>
                                </form>
                              </div>
                              
                              <!-- Replies Section -->
                              <?php if (!empty($replies[$comment['id']])): ?>
                                <div class="modern-comment-replies">
                                  <?php foreach ($replies[$comment['id']] as $reply): ?>
                                    <!-- Single Reply -->
                                    <div class="modern-comment-reply-item" data-comment-id="<?php echo $reply['id']; ?>">
                                      <!-- Reply Author -->
                                      <div class="modern-comment-author">
                                        <div class="author-avatar" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                          A
                                        </div>
                                        <div class="author-info">
                                          <h6 class="author-name">Anonymous replied</h6>
                                          <p class="comment-time">
                                            <?php 
                                            $rd = new DateTime($reply['created_at']);
                                            echo $rd->format('M j, Y');
                                            ?>
                                          </p>
                                        </div>
                                      </div>
                                      
                                      <!-- Reply Content -->
                                      <div class="modern-comment-content">
                                        <?php echo htmlspecialchars($reply['content']); ?>
                                      </div>
                                      
                                      <!-- Reply Actions -->
                                      <div class="modern-comment-actions">
                                        <!-- Facebook Reaction Button Container -->
                                        <div class="fb-reaction-container" data-comment-id="<?php echo $reply['id']; ?>">
                                          <!-- Reaction button will be added by JavaScript -->
                                        </div>
                                        
                                        <button class="btn-action btn-edit" data-comment-id="<?php echo $reply['id']; ?>" data-comment-text="<?php echo htmlspecialchars($reply['content']); ?>">
                                          <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn-action btn-delete" data-comment-id="<?php echo $reply['id']; ?>">
                                          <i class="fas fa-trash"></i> Delete
                                        </button>
                                      </div>
                                    </div>
                                  <?php endforeach; ?>
                                </div>
                              <?php endif; ?>
                            </div>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </div>
                      
                      <!-- Add Comment Form -->
                      <div class="add-comment-container">
                        <form method="POST" action="addcomments.php" class="add-comment-form" novalidate>
                          <input type="hidden" name="news_id" value="<?php echo $news['newsid']; ?>">
                          <div class="input-group">
                            <input type="text" class="form-control comment-input" name="comment_content" placeholder="Write a comment..." required minlength="2" maxlength="500">
                            <button type="submit" class="input-group-text">
                              <i class="fas fa-paper-plane me-1"></i> Post Comment
                            </button>
                          </div>
                          <div class="invalid-feedback d-block" style="display: none;">Comment must be between 2 and 500 characters.</div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="no-articles">
                <i class="far fa-newspaper"></i>
                <h4>No articles yet</h4>
                <p class="text-muted">Be the first to add an article!</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
          <div class="bg-white rounded p-4 shadow-sm mb-4">
            <h5 class="mb-3">Search</h5>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search articles" aria-label="Search">
              <button class="btn btn-primary" type="submit">Search</button>
            </form>
          </div>
          
          <div class="bg-white rounded p-4 shadow-sm mb-4">
            <h5 class="mb-3">Categories</h5>
            <ul class="list-unstyled mb-0">
              <li><a href="#" class="category-filter text-decoration-none d-block py-2 px-3 rounded active" data-cat="all"><strong>All Articles</strong></a></li>
              <li><a href="#" class="category-filter text-decoration-none d-block py-2 px-3 rounded" data-cat="education">Education</a></li>
              <li><a href="#" class="category-filter text-decoration-none d-block py-2 px-3 rounded" data-cat="sports">Sports</a></li>
              <li><a href="#" class="category-filter text-decoration-none d-block py-2 px-3 rounded" data-cat="technology">Technology</a></li>
              <li><a href="#" class="category-filter text-decoration-none d-block py-2 px-3 rounded" data-cat="community">Community</a></li>
              <li><a href="#" class="category-filter text-decoration-none d-block py-2 px-3 rounded" data-cat="health">Health</a></li>
              <li><a href="#" class="category-filter text-decoration-none d-block py-2 px-3 rounded" data-cat="events">Events</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Edit Article Form -->
      <?php if ($editNews): ?>
        <div class="row mt-5">
          <div class="col-12">
            <div class="bg-white rounded p-4 shadow-sm">
              <h2 class="section-title mb-4">Update Article</h2>
              <form action="gestionnews.php" method="POST" enctype="multipart/form-data" novalidate class="needs-validation" id="editArticleForm">
                <input type="hidden" name="update_id" value="<?php echo $editNews['newsid']; ?>">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editNews['image']); ?>">
                
                <div class="mb-3">
                  <label for="editArticleImage" class="form-label">Article Image</label>
                  <?php if (!empty($editNews['image'])): ?>
                    <div class="mb-2">
                      <img src="../../../uploads/news/<?php echo htmlspecialchars($editNews['image']); ?>" alt="Current image" style="max-width: 200px; height: auto;" class="img-thumbnail">
                      <p class="text-muted small mb-0">Existing image above. You must keep it or choose a new one.</p>
                    </div>
                  <?php endif; ?>
                  <input class="form-control" type="file" id="editArticleImage" name="image">
                  <small id="editArticleImageHelp" class="form-text text-muted">An image is mandatory. Select a new one if replacing.</small>
                  <div class="invalid-feedback">Please keep the existing image or choose a new one.</div>
                </div>
                <div class="mb-3">
                  <label for="editArticleTitle" class="form-label">Title</label>
                  <input type="text" class="form-control" id="editArticleTitle" name="title" 
                         value="<?php echo htmlspecialchars($editNews['title']); ?>" required minlength="3" maxlength="200">
                  <small id="editArticleTitleHelp" class="form-text text-muted">Enter a clear, descriptive title (min 3 characters).</small>
                  <div class="invalid-feedback">Title must be at least 3 characters.</div>
                </div>
                <div class="mb-3">
                  <label for="editArticleCategory" class="form-label">Category</label>
                  <select class="form-select" id="editArticleCategory" name="category" required>
                    <option value="">Select a category</option>
                    <option value="community" <?php echo ($editNews['category'] == 'community') ? 'selected' : ''; ?>>Community</option>
                    <option value="education" <?php echo ($editNews['category'] == 'education') ? 'selected' : ''; ?>>Education</option>
                    <option value="events" <?php echo ($editNews['category'] == 'events') ? 'selected' : ''; ?>>Events</option>
                    <option value="sports" <?php echo ($editNews['category'] == 'sports') ? 'selected' : ''; ?>>Sports</option>
                    <option value="technology" <?php echo ($editNews['category'] == 'technology') ? 'selected' : ''; ?>>Technology</option>
                    <option value="health" <?php echo ($editNews['category'] == 'health') ? 'selected' : ''; ?>>Health</option>
                    <option value="business" <?php echo ($editNews['category'] == 'business') ? 'selected' : ''; ?>>Business</option>
                  </select>
                  <small id="editArticleCategoryHelp" class="form-text text-muted">Choose the most relevant category.</small>
                  <div class="invalid-feedback">Please select a category.</div>
                </div>
                
                <div class="mb-3">
                  <label for="editArticleContent" class="form-label">Content</label>
                  <textarea class="form-control" id="editArticleContent" name="content" rows="5" required minlength="20"><?php echo htmlspecialchars($editNews['content']); ?></textarea>
                  <small id="editArticleContentHelp" class="form-text text-muted">Write the full article content (min 20 characters).</small>
                  <div class="invalid-feedback">Content must be at least 20 characters.</div>
                </div>
                <button type="submit" class="btn btn-warning">Update Article</button>
                <a href="gestionnews.php" class="btn btn-secondary">Cancel</a>
              </form>
            </div>
          </div>
        </div>
      <?php else: ?>
        <!-- Add New Article Form -->
        <div class="row mt-5">
          <div class="col-12">
            <div class="bg-white rounded p-4 shadow-sm">
              <h2 class="section-title mb-4">Add New Article</h2>
              <form action="gestionnews.php" method="POST" enctype="multipart/form-data" novalidate class="needs-validation" id="addArticleForm">
                <div class="mb-3">
                  <label for="articleImage" class="form-label">Article Image</label>
                  <input class="form-control" type="file" id="articleImage" name="image" required>
                  <small class="form-text text-muted">An image is required for new articles.</small>
                  <div class="invalid-feedback">Please choose an image.</div>
                </div>
                <div class="mb-3">
                  <label for="articleTitle" class="form-label">Title</label>
                  <input type="text" class="form-control" id="articleTitle" name="title" placeholder="Enter article title" required minlength="3" maxlength="200">
                  <small id="articleTitleHelp" class="form-text text-muted">Enter a clear, descriptive title (min 3 characters).</small>
                  <div class="invalid-feedback">Title must be at least 3 characters.</div>
                </div>
                <div class="mb-3">
                  <label for="articleCategory" class="form-label">Category</label>
                  <select class="form-select" id="articleCategory" name="category" required>
                    <option value="">Select a category</option>
                    <option value="community">Community</option>
                    <option value="education">Education</option>
                    <option value="events">Events</option>
                    <option value="sports">Sports</option>
                    <option value="technology">Technology</option>
                    <option value="health">Health</option>
                    <option value="business">Business</option>
                  </select>
                  <small id="articleCategoryHelp" class="form-text text-muted">Choose the most relevant category.</small>
                  <div class="invalid-feedback">Please select a category.</div>
                </div>
                
                <div class="mb-3">
                  <label for="articleContent" class="form-label">Content</label>
                  <textarea class="form-control" id="articleContent" name="content" rows="5" placeholder="Enter article content" required minlength="20"></textarea>
                  <small id="articleContentHelp" class="form-text text-muted">Write the full article content (min 20 characters).</small>
                  <div class="invalid-feedback">Content must be at least 20 characters.</div>
                </div>
                <button type="submit" class="btn btn-primary">Add Article</button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </main>

    <!-- AI Generate Modal -->
    <div class="ai-generate-modal" id="aiGenerateModal">
      <div class="ai-generate-content">
        <div class="ai-generate-header">
          <h5><i class="fas fa-robot"></i> Generate AI Summary</h5>
          <button type="button" class="btn-close btn-close-white" id="closeAIModal"></button>
        </div>
        <div class="ai-generate-body">
          <div class="ai-generate-preview">
            <h6>Article Preview:</h6>
            <p id="aiArticlePreview"></p>
          </div>
          <div class="mb-3">
            <label for="aiSummaryLength" class="form-label">Summary Length</label>
            <select class="form-select" id="aiSummaryLength">
              <option value="short">Short (1-2 sentences)</option>
              <option value="medium" selected>Medium (3-4 sentences)</option>
              <option value="long">Long (5-6 sentences)</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="aiModel" class="form-label">Summarization Method</label>
            <select class="form-select" id="aiModel">
              <option value="local-extractive">Extractive Algorithm (Fast)</option>
              <option value="local-tfidf">TF-IDF Algorithm (Balanced)</option>
              <option value="simple">First Sentences (Simple)</option>
            </select>
            <small class="text-muted">All processing happens locally in your browser</small>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="includeBulletPoints">
            <label class="form-check-label" for="includeBulletPoints">
              Include bullet points
            </label>
          </div>
        </div>
        <div class="ai-generate-footer">
          <button type="button" class="btn btn-secondary" id="cancelAIGenerate">Cancel</button>
          <button type="button" class="btn btn-primary" id="generateAISummary">Generate Summary</button>
        </div>
      </div>
    </div>

    <footer class="container-xxl py-5">
      <div class="container">
        <div class="text-center">
          <p class="mb-0">&copy; 2025 News — Integrated with Kider template</p>
        </div>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../kider-1.0.0/js/main.js"></script>
  
  <script>
// AI Summarization System - COMPLETELY LOCAL (NO API KEY NEEDED)
class AISummarizationSystem {
    constructor() {
        this.apiCallsToday = parseInt(localStorage.getItem('ai_api_calls_today') || '0');
        this.tokensUsed = parseInt(localStorage.getItem('ai_tokens_used') || '0');
        this.lastCallDate = localStorage.getItem('ai_last_call_date') || '';
        this.init();
    }

    init() {
        const today = new Date().toDateString();
        if (this.lastCallDate !== today) {
            this.apiCallsToday = 0;
            this.tokensUsed = 0;
            this.lastCallDate = today;
            this.saveStats();
        }
        
        this.updateStatsDisplay();
        this.setupEventListeners();
        this.loadSavedSummaries();
    }

    setupEventListeners() {
        const aiSummarizeBtn = document.getElementById('aiSummarizeBtn');
        if (aiSummarizeBtn) {
            aiSummarizeBtn.addEventListener('click', () => {
                const sidebar = document.getElementById('aiControlsSidebar');
                sidebar.classList.toggle('show');
            });
        }

        const closeAIControls = document.getElementById('closeAIControls');
        if (closeAIControls) {
            closeAIControls.addEventListener('click', () => {
                const sidebar = document.getElementById('aiControlsSidebar');
                sidebar.classList.remove('show');
            });
        }

        const generateAllBtn = document.getElementById('generateAllSummaries');
        if (generateAllBtn) {
            generateAllBtn.addEventListener('click', () => {
                this.generateAllArticleSummaries();
            });
        }

        document.querySelectorAll('.length-option').forEach(option => {
            option.addEventListener('click', (e) => {
                document.querySelectorAll('.length-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                e.target.classList.add('active');
            });
        });

        const aiModal = document.getElementById('aiGenerateModal');
        const closeAIModal = document.getElementById('closeAIModal');
        const cancelAIGenerate = document.getElementById('cancelAIGenerate');

        if (closeAIModal) {
            closeAIModal.addEventListener('click', () => {
                aiModal.classList.remove('show');
            });
        }

        if (cancelAIGenerate) {
            cancelAIGenerate.addEventListener('click', () => {
                aiModal.classList.remove('show');
            });
        }

        const generateAISummaryBtn = document.getElementById('generateAISummary');
        if (generateAISummaryBtn) {
            generateAISummaryBtn.addEventListener('click', () => {
                this.generateCurrentArticleSummary();
            });
        }

        document.addEventListener('click', (e) => {
            if (e.target.closest('.ai-summary-toggle')) {
                const button = e.target.closest('.ai-summary-toggle');
                const articleId = button.dataset.articleId;
                this.toggleAISummary(articleId);
            }

            if (e.target.closest('.ai-summary-btn')) {
                const button = e.target.closest('.ai-summary-btn');
                const articleId = button.dataset.articleId;
                const articleTitle = button.dataset.articleTitle;
                const articleContent = button.dataset.articleContent;
                
                if (articleId && articleContent) {
                    this.showGenerateModal(articleId, articleTitle, articleContent);
                }
            }

            if (e.target.closest('.copy-btn')) {
                const button = e.target.closest('.copy-btn');
                const targetId = button.dataset.target;
                this.copyAISummary(targetId);
            }

            if (e.target.closest('.regenerate-btn')) {
                const button = e.target.closest('.regenerate-btn');
                const articleId = button.dataset.articleId;
                this.regenerateAISummary(articleId);
            }
        });
    }

    showGenerateModal(articleId, articleTitle, articleContent) {
        const modal = document.getElementById('aiGenerateModal');
        const preview = document.getElementById('aiArticlePreview');
        
        const truncatedContent = articleContent.length > 200 
            ? articleContent.substring(0, 200) + '...' 
            : articleContent;
        
        preview.textContent = truncatedContent;
        modal.dataset.currentArticleId = articleId;
        modal.dataset.currentArticleContent = articleContent;
        modal.classList.add('show');
    }

    async generateCurrentArticleSummary() {
        const modal = document.getElementById('aiGenerateModal');
        const articleId = modal.dataset.currentArticleId;
        const articleContent = modal.dataset.currentArticleContent;
        const length = document.getElementById('aiSummaryLength').value;
        const model = document.getElementById('aiModel').value;
        const includeBulletPoints = document.getElementById('includeBulletPoints').checked;
        
        if (!articleId || !articleContent) {
            this.showError('No article content provided');
            return;
        }

        modal.classList.remove('show');
        await this.generateArticleSummary(articleId, articleContent, length, model, includeBulletPoints);
    }

    async generateArticleSummary(articleId, content, length = 'medium', model = 'local-extractive', includeBulletPoints = false) {
        const summaryContainer = document.getElementById(`ai-summary-${articleId}`);
        const contentElement = document.getElementById(`ai-summary-content-${articleId}`);
        const toggleButton = document.querySelector(`.ai-summary-toggle[data-article-id="${articleId}"]`);
        
        if (!summaryContainer || !contentElement) {
            console.error('Summary container not found for article:', articleId);
            return;
        }

        contentElement.innerHTML = `
            <div class="ai-summary-loading">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span>Generating AI summary...</span>
            </div>
        `;
        summaryContainer.classList.add('show');

        if (toggleButton) {
            toggleButton.innerHTML = '<i class="fas fa-robot"></i> Generating...';
            toggleButton.disabled = true;
        }

        try {
            // Use local summarizer (no API key needed)
            const summary = await this.callLocalSummarizer(content, length, model, includeBulletPoints);
            
            contentElement.innerHTML = summary;
            
            if (toggleButton) {
                toggleButton.innerHTML = '<i class="fas fa-robot"></i> Hide AI Summary';
                toggleButton.disabled = false;
            }

            this.saveSummary(articleId, {
                content: summary,
                length: length,
                model: model,
                timestamp: new Date().toISOString(),
                includeBulletPoints: includeBulletPoints
            });

            if (window.notificationSystem) {
                const articleTitle = document.querySelector(`.article-title[data-article-id="${articleId}"]`)?.textContent || 'Article';
                window.notificationSystem.addNotification(
                    'ai', 
                    'AI Summary Generated', 
                    `AI summary generated for "${articleTitle}"`,
                    articleId
                );
            }

            this.apiCallsToday++;
            this.saveStats();
            this.updateStatsDisplay();

        } catch (error) {
            console.error('Error generating summary:', error);
            
            // Fallback to simple summary
            const fallbackSummary = this.generateSimpleSummary(content, length);
            contentElement.innerHTML = fallbackSummary;
            
            if (toggleButton) {
                toggleButton.innerHTML = '<i class="fas fa-robot"></i> Show AI Summary';
                toggleButton.disabled = false;
            }
        }
    }

    // LOCAL SUMMARIZER - NO API KEY NEEDED
    async callLocalSummarizer(content, length, model, includeBulletPoints) {
        // Simple extractive summarization algorithm
        const lengthConfigs = {
            short: { maxSentences: 2, instruction: 'Summarize in 1-2 concise sentences.' },
            medium: { maxSentences: 4, instruction: 'Summarize in 3-4 informative sentences.' },
            long: { maxSentences: 6, instruction: 'Summarize in 5-6 detailed sentences.' }
        };
        
        const config = lengthConfigs[length] || lengthConfigs.medium;
        
        // Clean and split text into sentences
        const sentences = this.extractSentences(content);
        
        // Score sentences (simple algorithm: length + keyword density)
        const scoredSentences = sentences.map((sentence, index) => ({
            sentence: sentence,
            score: this.scoreSentence(sentence, content),
            index: index
        }));
        
        // Sort by score (highest first)
        scoredSentences.sort((a, b) => b.score - a.score);
        
        // Take top sentences and reorder to maintain original flow
        const topSentences = scoredSentences
            .slice(0, config.maxSentences)
            .sort((a, b) => a.index - b.index)
            .map(s => s.sentence);
        
        // Format summary
        let summary = topSentences.join('. ') + '.';
        
        // Add bullet points if requested
        if (includeBulletPoints) {
            summary = '• ' + summary.replace(/\. /g, '\n• ');
        }
        
        // Add info about local processing
        summary += `\n\n📝 <em>Generated locally with ${model} algorithm</em>`;
        
        // Simulate API usage stats
        this.apiCallsToday++;
        this.tokensUsed += summary.split(' ').length * 1.3; // Approximate token count
        this.saveStats();
        this.updateStatsDisplay();
        
        return summary;
    }

    extractSentences(text) {
        // Split by sentence endings, keeping abbreviations in mind
        const sentences = text.split(/(?<=[.!?])\s+(?=[A-Z])/);
        return sentences
            .map(s => s.trim())
            .filter(s => s.length > 10); // Filter out very short fragments
    }

    scoreSentence(sentence, fullText) {
        let score = 0;
        
        // 1. Length scoring (medium-length sentences are often good)
        const wordCount = sentence.split(/\s+/).length;
        if (wordCount > 8 && wordCount < 30) {
            score += 3;
        } else if (wordCount >= 30) {
            score += 2;
        } else {
            score += 1;
        }
        
        // 2. Position scoring (first and last sentences are often important)
        const sentences = this.extractSentences(fullText);
        const index = sentences.indexOf(sentence);
        if (index === 0) score += 2; // First sentence
        if (index === sentences.length - 1) score += 1; // Last sentence
        
        // 3. Keyword scoring (words that appear frequently in the full text)
        const words = sentence.toLowerCase().split(/\s+/).filter(w => w.length > 3);
        const allWords = fullText.toLowerCase().split(/\s+/).filter(w => w.length > 3);
        
        const wordFreq = {};
        allWords.forEach(word => {
            wordFreq[word] = (wordFreq[word] || 0) + 1;
        });
        
        words.forEach(word => {
            if (wordFreq[word] > 2) {
                score += 1;
            }
        });
        
        // 4. Question words (questions are often important)
        if (/^(what|why|how|when|where|who)\s/i.test(sentence)) {
            score += 1;
        }
        
        return score;
    }

    generateSimpleSummary(text, length) {
        const sentences = text.split(/[.!?]+/).filter(s => s.trim());
        
        const lengthMap = {
            short: 2,
            medium: 3,
            long: 4
        };
        
        const count = lengthMap[length] || 3;
        
        // Just take first N sentences (simple but effective)
        const summary = sentences.slice(0, count).join('. ') + '.';
        
        return `
            <div class="ai-summary-content simple">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Local AI Summary:</strong><br>
                ${summary}
                <div class="mt-2 text-muted small">
                    <i class="fas fa-laptop-code me-1"></i>
                    Generated locally with simple algorithm
                </div>
            </div>
        `;
    }

    async generateAllArticleSummaries() {
        const articles = document.querySelectorAll('.article-item');
        const model = document.getElementById('aiModelSelect').value;
        const lengthOption = document.querySelector('.length-option.active');
        const length = lengthOption ? lengthOption.dataset.length : 'medium';
        
        let completed = 0;
        const total = articles.length;

        const originalText = document.getElementById('generateAllSummaries').innerHTML;
        document.getElementById('generateAllSummaries').innerHTML = `
            <i class="fas fa-spinner fa-spin"></i> Generating (0/${total})...
        `;
        document.getElementById('generateAllSummaries').disabled = true;

        for (const article of articles) {
            const articleId = article.querySelector('.ai-summary-btn')?.dataset.articleId;
            const articleContent = article.querySelector('.article-excerpt')?.textContent;
            
            if (articleId && articleContent) {
                try {
                    await this.generateArticleSummary(articleId, articleContent, length, model, false);
                    completed++;
                    
                    document.getElementById('generateAllSummaries').innerHTML = `
                        <i class="fas fa-spinner fa-spin"></i> Generating (${completed}/${total})...
                    `;
                    
                    await new Promise(resolve => setTimeout(resolve, 1000));
                } catch (error) {
                    console.error(`Error generating summary for article ${articleId}:`, error);
                }
            }
        }

        document.getElementById('generateAllSummaries').innerHTML = originalText;
        document.getElementById('generateAllSummaries').disabled = false;

        if (window.notificationSystem) {
            window.notificationSystem.addNotification(
                'ai',
                'Batch Summary Complete',
                `Generated AI summaries for ${completed} out of ${total} articles.`,
                null
            );
        }
    }

    toggleAISummary(articleId) {
        const summaryContainer = document.getElementById(`ai-summary-${articleId}`);
        const toggleButton = document.querySelector(`.ai-summary-toggle[data-article-id="${articleId}"]`);
        
        if (!summaryContainer) return;

        if (summaryContainer.classList.contains('show')) {
            summaryContainer.classList.remove('show');
            if (toggleButton) {
                toggleButton.innerHTML = '<i class="fas fa-robot"></i> Show AI Summary';
            }
        } else {
            summaryContainer.classList.add('show');
            if (toggleButton) {
                toggleButton.innerHTML = '<i class="fas fa-robot"></i> Hide AI Summary';
            }
            
            const contentElement = document.getElementById(`ai-summary-content-${articleId}`);
            if (contentElement && !contentElement.textContent.trim()) {
                const articleContent = document.querySelector(`.article-excerpt[data-article-id="${articleId}"]`)?.textContent;
                if (articleContent) {
                    this.generateArticleSummary(articleId, articleContent);
                }
            }
        }
    }

    copyAISummary(targetId) {
        const summaryContainer = document.getElementById(targetId);
        if (!summaryContainer) return;

        const contentElement = summaryContainer.querySelector('.ai-summary-content');
        if (!contentElement) return;

        const textToCopy = contentElement.textContent;
        
        navigator.clipboard.writeText(textToCopy).then(() => {
            const copyButton = summaryContainer.querySelector('.copy-btn');
            if (copyButton) {
                const originalHTML = copyButton.innerHTML;
                copyButton.innerHTML = '<i class="fas fa-check"></i> Copied!';
                copyButton.classList.add('copied');
                
                setTimeout(() => {
                    copyButton.innerHTML = originalHTML;
                    copyButton.classList.remove('copied');
                }, 2000);
            }
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            alert('Failed to copy text to clipboard');
        });
    }

    async regenerateAISummary(articleId) {
        const articleContent = document.querySelector(`.article-excerpt[data-article-id="${articleId}"]`)?.textContent;
        if (articleContent) {
            const model = document.getElementById('aiModelSelect').value;
            const lengthOption = document.querySelector('.length-option.active');
            const length = lengthOption ? lengthOption.dataset.length : 'medium';
            
            await this.generateArticleSummary(articleId, articleContent, length, model, false);
        }
    }

    saveSummary(articleId, summaryData) {
        const summaries = JSON.parse(localStorage.getItem('article_summaries') || '{}');
        summaries[articleId] = summaryData;
        localStorage.setItem('article_summaries', JSON.stringify(summaries));
    }

    loadSavedSummaries() {
        const summaries = JSON.parse(localStorage.getItem('article_summaries') || '{}');
        
        Object.keys(summaries).forEach(articleId => {
            const summaryData = summaries[articleId];
            const contentElement = document.getElementById(`ai-summary-content-${articleId}`);
            
            if (contentElement) {
                contentElement.innerHTML = summaryData.content;
                
                const toggleButton = document.querySelector(`.ai-summary-toggle[data-article-id="${articleId}"]`);
                if (toggleButton && summaryData.content.trim()) {
                    toggleButton.innerHTML = '<i class="fas fa-robot"></i> Show AI Summary';
                }
            }
        });
    }

    saveStats() {
        localStorage.setItem('ai_api_calls_today', this.apiCallsToday.toString());
        localStorage.setItem('ai_tokens_used', this.tokensUsed.toString());
        localStorage.setItem('ai_last_call_date', this.lastCallDate);
    }

    updateStatsDisplay() {
        const apiCallsElement = document.getElementById('apiCallsCount');
        const tokensElement = document.getElementById('tokensUsed');
        
        if (apiCallsElement) {
            apiCallsElement.textContent = this.apiCallsToday;
        }
        
        if (tokensElement) {
            tokensElement.textContent = this.tokensUsed.toLocaleString();
        }
    }

    showError(message) {
        const toast = document.createElement('div');
        toast.className = 'toast position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">AI Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
}

// COMPLETE NOTIFICATION SYSTEM
class NotificationSystem {
    constructor() {
        this.notifications = JSON.parse(localStorage.getItem('articleNotifications') || '[]');
        this.bell = document.getElementById('notificationBell');
        this.dropdown = document.getElementById('notificationsDropdown');
        this.list = document.getElementById('notificationsList');
        this.init();
    }

    init() {
        this.renderNotifications();
        this.setupEventListeners();
        this.updateBellBadge();
        this.setupFacebookReactions();
        this.addReactionButtonsToArticles();
        this.addReactionButtonsToComments();
    }

    setupEventListeners() {
        // Notification bell click
        if (this.bell) {
            this.bell.addEventListener('click', (e) => {
                e.stopPropagation();
                this.dropdown.classList.toggle('show');
                this.markAllAsRead();
            });
        }

        // Mark all as read button
        const markAllReadBtn = document.getElementById('markAllRead');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.markAllAsRead();
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.bell.contains(e.target) && !this.dropdown.contains(e.target)) {
                this.dropdown.classList.remove('show');
            }
        });

        // View all notifications
        const viewAllBtn = document.getElementById('viewAllNotifications');
        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.dropdown.classList.remove('show');
                // In a real app, this would navigate to notifications page
                alert('In a complete app, this would show all notifications in a separate page.');
            });
        }
    }

    renderNotifications() {
        if (!this.list) return;

        if (this.notifications.length === 0) {
            this.list.innerHTML = `
                <div class="empty-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <p>No notifications yet</p>
                </div>
            `;
            return;
        }

        this.list.innerHTML = '';
        this.notifications.slice(0, 10).forEach(notification => {
            const notificationEl = document.createElement('div');
            notificationEl.className = `notification-item ${notification.unread ? 'unread' : ''}`;
            notificationEl.innerHTML = `
                <div class="notification-icon ${notification.type}">
                    <i class="fas fa-${this.getNotificationIcon(notification.type)}"></i>
                </div>
                <div class="notification-content">
                    <h6>${notification.title}</h6>
                    <p>${notification.message}</p>
                    <div class="notification-time">${this.formatTime(notification.timestamp)}</div>
                </div>
            `;
            notificationEl.addEventListener('click', () => {
                this.handleNotificationClick(notification);
            });
            this.list.appendChild(notificationEl);
        });
    }

    getNotificationIcon(type) {
        const icons = {
            'new-article': 'file-alt',
            'update': 'edit',
            'reaction': 'heart',
            'ai': 'robot',
            'comment': 'comment',
            'reply': 'reply',
            'default': 'bell'
        };
        return icons[type] || icons.default;
    }

    formatTime(timestamp) {
        const now = new Date();
        const diff = now - new Date(timestamp);
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        return new Date(timestamp).toLocaleDateString();
    }

    addNotification(type, title, message, targetId = null) {
        const notification = {
            id: Date.now(),
            type: type,
            title: title,
            message: message,
            timestamp: new Date().toISOString(),
            unread: true,
            targetId: targetId
        };

        this.notifications.unshift(notification);
        if (this.notifications.length > 50) {
            this.notifications = this.notifications.slice(0, 50);
        }

        this.saveNotifications();
        this.renderNotifications();
        this.updateBellBadge();
        
        // Show bell animation
        if (this.bell) {
            this.bell.classList.add('has-notifications');
            this.bell.style.animation = 'shake 0.5s';
            setTimeout(() => {
                this.bell.style.animation = '';
            }, 500);
        }
    }

    addCommentNotification(articleId, commentText) {
        const articleTitle = document.querySelector(`[data-article-id="${articleId}"] .article-title`)?.textContent || 'Article';
        this.addNotification(
            'comment',
            'New Comment',
            `Someone commented on "${articleTitle}": "${commentText.substring(0, 50)}..."`,
            articleId
        );
    }

    addReplyNotification(articleTitle, commentAuthor, commentContent, replyText) {
        this.addNotification(
            'reply',
            'New Reply',
            `Someone replied to ${commentAuthor}'s comment on "${articleTitle}": "${replyText.substring(0, 50)}..."`
        );
    }

    addCommentUpdatedNotification(articleId, commentId, oldText, newText) {
        const articleTitle = document.querySelector(`[data-article-id="${articleId}"] .article-title`)?.textContent || 'Article';
        this.addNotification(
            'update',
            'Comment Updated',
            `Comment updated on "${articleTitle}": "${newText.substring(0, 50)}..."`,
            articleId
        );
    }

    addCommentDeletedNotification(articleId, commentId, commentText) {
        const articleTitle = document.querySelector(`[data-article-id="${articleId}"] .article-title`)?.textContent || 'Article';
        this.addNotification(
            'comment',
            'Comment Deleted',
            `Comment deleted from "${articleTitle}": "${commentText.substring(0, 50)}..."`,
            articleId
        );
    }

    handleNotificationClick(notification) {
        notification.unread = false;
        this.saveNotifications();
        this.renderNotifications();
        this.updateBellBadge();

        if (notification.targetId) {
            const articleElement = document.querySelector(`[data-article-id="${notification.targetId}"]`);
            if (articleElement) {
                // Scroll to article
                articleElement.scrollIntoView({ behavior: 'smooth' });
                
                // Open comments if it's a comment notification
                if (notification.type === 'comment' || notification.type === 'reply') {
                    const commentsToggle = articleElement.querySelector('.view-comments-toggle');
                    if (commentsToggle && commentsToggle.classList.contains('collapsed')) {
                        commentsToggle.click();
                    }
                }
            }
        }

        this.dropdown.classList.remove('show');
    }

    markAllAsRead() {
        this.notifications.forEach(notification => {
            notification.unread = false;
        });
        this.saveNotifications();
        this.renderNotifications();
        this.updateBellBadge();
    }

    clearAllNotifications() {
        this.notifications = [];
        this.saveNotifications();
        this.renderNotifications();
        this.updateBellBadge();
    }

    updateBellBadge() {
        const unreadCount = this.notifications.filter(n => n.unread).length;
        let badge = this.bell.querySelector('.notification-badge');
        
        if (unreadCount > 0) {
            this.bell.classList.add('has-notifications');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'notification-badge';
                this.bell.appendChild(badge);
            }
            badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
        } else {
            this.bell.classList.remove('has-notifications');
            if (badge) {
                badge.remove();
            }
        }
    }

    saveNotifications() {
        localStorage.setItem('articleNotifications', JSON.stringify(this.notifications));
    }

    // FACEBOOK REACTION SYSTEM
    setupFacebookReactions() {
        // Add reaction emojis
        this.reactions = [
            { type: 'like', emoji: '👍', label: 'Like', color: '#1877f2' },
            { type: 'love', emoji: '❤️', label: 'Love', color: '#f33e58' },
            { type: 'haha', emoji: '😄', label: 'Haha', color: '#f7b125' },
            { type: 'wow', emoji: '😲', label: 'Wow', color: '#f7b125' },
            { type: 'sad', emoji: '😢', label: 'Sad', color: '#f7b125' },
            { type: 'angry', emoji: '😠', label: 'Angry', color: '#e4715a' }
        ];
    }

    addReactionButtonsToArticles() {
        document.querySelectorAll('.fb-reaction-container[data-article-id]').forEach(container => {
            const articleId = container.getAttribute('data-article-id');
            this.createReactionButton(container, articleId, 'article');
        });
    }

    addReactionButtonsToComments() {
        document.querySelectorAll('.fb-reaction-container[data-comment-id]').forEach(container => {
            const commentId = container.getAttribute('data-comment-id');
            this.createReactionButton(container, commentId, 'comment');
        });
    }

    createReactionButton(container, targetId, targetType) {
        // Get existing reactions from localStorage
        const storageKey = `reactions_${targetType}_${targetId}`;
        const existingReactions = JSON.parse(localStorage.getItem(storageKey) || '{}');
        
        // Create main like button
        const likeBtn = document.createElement('button');
        likeBtn.className = 'fb-like-btn';
        likeBtn.type = 'button';
        
        // Set button text based on existing reaction
        const userReaction = existingReactions['user'] || 'like';
        const reactionCount = Object.keys(existingReactions).filter(k => k !== 'user').length;
        
        likeBtn.innerHTML = `
            <span class="reaction-emoji">${this.reactions.find(r => r.type === userReaction)?.emoji || '👍'}</span>
            <span class="reaction-text">${this.reactions.find(r => r.type === userReaction)?.label || 'Like'}</span>
            ${reactionCount > 0 ? `<span class="reaction-count">${reactionCount}</span>` : ''}
        `;
        
        // Create reaction panel
        const panel = document.createElement('div');
        panel.className = 'fb-reaction-panel';
        
        this.reactions.forEach(reaction => {
            const btn = document.createElement('button');
            btn.className = `fb-reaction-btn fb-${reaction.type}`;
            btn.innerHTML = reaction.emoji;
            btn.title = reaction.label;
            btn.dataset.reaction = reaction.type;
            btn.type = 'button';
            
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.handleReaction(targetId, targetType, reaction.type, likeBtn, panel);
                panel.classList.remove('show');
            });
            
            panel.appendChild(btn);
        });
        
        // Show panel on hover
        likeBtn.addEventListener('mouseenter', (e) => {
            e.stopPropagation();
            
            // Hide other panels
            document.querySelectorAll('.fb-reaction-panel').forEach(p => p.classList.remove('show'));
            
            // Show this panel with animation
            panel.classList.add('show');
            
            // Position panel
            if (container.closest('.article-footer')) {
                panel.style.bottom = '100%';
                panel.style.right = '0';
                panel.style.left = 'auto';
            } else {
                panel.style.bottom = '100%';
                panel.style.left = '0';
                panel.style.right = 'auto';
            }
        });
        
        // Keep panel visible when hovering over it
        panel.addEventListener('mouseenter', (e) => {
            e.stopPropagation();
            panel.classList.add('show');
        });
        
        // Hide panel when mouse leaves
        likeBtn.addEventListener('mouseleave', (e) => {
            setTimeout(() => {
                if (!panel.matches(':hover')) {
                    panel.classList.remove('show');
                }
            }, 300);
        });
        
        panel.addEventListener('mouseleave', (e) => {
            panel.classList.remove('show');
        });
        
        // Also handle click to react with like
        likeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!panel.classList.contains('show')) {
                // React with like
                this.handleReaction(targetId, targetType, 'like', likeBtn, panel);
            }
        });
        
        container.appendChild(likeBtn);
        container.appendChild(panel);
        
        // Store reference for updating
        likeBtn.dataset.targetId = targetId;
        likeBtn.dataset.targetType = targetType;
    }

    handleReaction(targetId, targetType, reactionType, likeBtn, panel) {
        const storageKey = `reactions_${targetType}_${targetId}`;
        const existingReactions = JSON.parse(localStorage.getItem(storageKey) || '{}');
        const previousReaction = existingReactions['user'];
        
        // Update reaction
        existingReactions['user'] = reactionType;
        existingReactions[Date.now()] = reactionType; // Add timestamped reaction
        
        // Remove old reactions (keep only latest 50)
        const keys = Object.keys(existingReactions);
        if (keys.length > 51) { // 1 user reaction + 50 others
            const oldestKey = keys.find(k => k !== 'user');
            if (oldestKey) {
                delete existingReactions[oldestKey];
            }
        }
        
        localStorage.setItem(storageKey, JSON.stringify(existingReactions));
        
        // Update button
        const reaction = this.reactions.find(r => r.type === reactionType);
        const reactionCount = Object.keys(existingReactions).filter(k => k !== 'user').length;
        
        likeBtn.innerHTML = `
            <span class="reaction-emoji">${reaction.emoji}</span>
            <span class="reaction-text">${reaction.label}</span>
            ${reactionCount > 0 ? `<span class="reaction-count">${reactionCount}</span>` : ''}
        `;
        
        // Add notification if it's a new reaction (not the same as before)
        if (previousReaction !== reactionType) {
            let targetName = '';
            if (targetType === 'article') {
                const articleTitle = document.querySelector(`[data-article-id="${targetId}"] .article-title`)?.textContent || 'Article';
                targetName = articleTitle;
            } else {
                targetName = 'comment';
            }
            
            this.addNotification(
                'reaction',
                'New Reaction',
                `Someone reacted with ${reaction.label} to ${targetName}`,
                targetType === 'article' ? targetId : null
            );
        }
        
        // Add visual feedback
        likeBtn.style.transform = 'scale(1.1)';
        setTimeout(() => {
            likeBtn.style.transform = '';
        }, 200);
    }
}

// Initialize systems when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AI Summarization System
    window.aiSummarizationSystem = new AISummarizationSystem();
    
    // Initialize Notification System
    window.notificationSystem = new NotificationSystem();
    
    // Form Validation for Add Article Form
    const addArticleForm = document.getElementById('addArticleForm');
    if (addArticleForm) {
        addArticleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Remove previous validation states
            this.classList.remove('was-validated');
            
            // Validate form
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return false;
            }
            
            // Validate image file
            const imageInput = document.getElementById('articleImage');
            if (imageInput.files.length === 0) {
                imageInput.classList.add('is-invalid');
                imageInput.classList.remove('is-valid');
                const feedback = imageInput.nextElementSibling;
                feedback.textContent = 'Please choose an image.';
                feedback.style.display = 'block';
                e.stopPropagation();
                return false;
            } else {
                imageInput.classList.add('is-valid');
                imageInput.classList.remove('is-invalid');
                const feedback = imageInput.nextElementSibling;
                feedback.style.display = 'none';
            }
            
            // All validations passed
            const title = document.getElementById('articleTitle').value;
            sessionStorage.setItem('newArticleTitle', title);
            
            // Submit form
            this.submit();
        });
        
        // Real-time validation for inputs
        const inputs = addArticleForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    }
    
    // Form Validation for Edit Article Form
    const editArticleForm = document.getElementById('editArticleForm');
    if (editArticleForm) {
        editArticleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Remove previous validation states
            this.classList.remove('was-validated');
            
            // Validate form
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return false;
            }
            
            // All validations passed
            const title = document.getElementById('editArticleTitle').value;
            sessionStorage.setItem('updatedArticleTitle', title);
            
            // Submit form
            this.submit();
        });
        
        // Real-time validation for inputs
        const inputs = editArticleForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    }
    
    // Handle form submissions for notifications
    const addCommentForms = document.querySelectorAll('.add-comment-form');
    addCommentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const commentInput = this.querySelector('.comment-input');
            const newsId = this.querySelector('input[name="news_id"]').value;
            
            if (commentInput && commentInput.value.trim()) {
                sessionStorage.setItem('newCommentData', JSON.stringify({
                    newsId: newsId,
                    commentText: commentInput.value.trim()
                }));
            }
        });
    });

    const replyForms = document.querySelectorAll('.reply-form');
    replyForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const replyInput = this.querySelector('.reply-input');
            const newsId = this.querySelector('input[name="news_id"]').value;
            const replyTo = this.querySelector('input[name="reply_to"]').value;
            
            if (replyInput && replyInput.value.trim()) {
                const commentItem = document.querySelector(`[data-comment-id="${replyTo}"]`);
                if (commentItem) {
                    const commentAuthor = commentItem.querySelector('.author-name').textContent;
                    const commentContent = commentItem.querySelector('.modern-comment-content').textContent;
                    const articleTitle = commentItem.closest('.comments-section').querySelector('.article-title')?.textContent || 'Article';
                    
                    // Store reply data for notification
                    sessionStorage.setItem('newReplyData', JSON.stringify({
                        articleTitle: articleTitle,
                        commentAuthor: commentAuthor,
                        commentContent: commentContent,
                        replyText: replyInput.value.trim(),
                        newsId: newsId
                    }));
                }
            }
        });
    });

    // Check for notifications after page load
    setTimeout(() => {
        // Check for new article notification
        if (sessionStorage.getItem('newArticleTitle')) {
            const title = sessionStorage.getItem('newArticleTitle');
            window.notificationSystem.addNotification('new-article', 'New Article', title);
            sessionStorage.removeItem('newArticleTitle');
        }

        // Check for updated article notification
        if (sessionStorage.getItem('updatedArticleTitle')) {
            const title = sessionStorage.getItem('updatedArticleTitle');
            window.notificationSystem.addNotification('update', 'Article Updated', title);
            sessionStorage.removeItem('updatedArticleTitle');
        }

        // Check URL parameters for comment/reply success
        const urlParams = new URLSearchParams(window.location.search);
        
        // Check for new comment
        if (urlParams.has('comment_success') && sessionStorage.getItem('newCommentData')) {
            const { newsId, commentText } = JSON.parse(sessionStorage.getItem('newCommentData'));
            window.notificationSystem.addCommentNotification(newsId, commentText);
            sessionStorage.removeItem('newCommentData');
        }
        
        // Check for new reply (FIXED: This was missing before)
        if (urlParams.has('reply_success') && sessionStorage.getItem('newReplyData')) {
            const { articleTitle, commentAuthor, commentContent, replyText, newsId } = JSON.parse(sessionStorage.getItem('newReplyData'));
            window.notificationSystem.addReplyNotification(articleTitle, commentAuthor, commentContent, replyText);
            window.notificationSystem.addCommentNotification(newsId, replyText); // Also add as comment notification
            sessionStorage.removeItem('newReplyData');
        }
        
        // Check for comment edit
        if (sessionStorage.getItem('editCommentData')) {
            const { articleId, commentId, oldText } = JSON.parse(sessionStorage.getItem('editCommentData'));
            const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
            if (commentItem) {
                const newText = commentItem.querySelector('.modern-comment-content').textContent;
                if (oldText !== newText) {
                    window.notificationSystem.addCommentUpdatedNotification(articleId, commentId, oldText, newText);
                }
            }
            sessionStorage.removeItem('editCommentData');
        }
        
        // Check for comment delete
        if (sessionStorage.getItem('deleteCommentData')) {
            const { articleId, commentId, commentText } = JSON.parse(sessionStorage.getItem('deleteCommentData'));
            window.notificationSystem.addCommentDeletedNotification(articleId, commentId, commentText);
            sessionStorage.removeItem('deleteCommentData');
        }
    }, 1000);

    // Add clear notifications button
    const clearButton = document.createElement('button');
    clearButton.className = 'btn btn-sm btn-outline-danger ms-2';
    clearButton.innerHTML = '<small>Clear All</small>';
    clearButton.addEventListener('click', (e) => {
        e.stopPropagation();
        if (confirm('Are you sure you want to clear all notifications?')) {
            window.notificationSystem.clearAllNotifications();
        }
    });
    
    const notificationsHeader = document.querySelector('.notifications-header');
    if (notificationsHeader) {
        notificationsHeader.appendChild(clearButton);
    }

    // Modern Comment System JavaScript
    document.querySelectorAll('.btn-reply').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            
            document.querySelectorAll('.modern-comment-reply-form').forEach(form => {
                form.classList.remove('show');
            });
            
            replyForm.classList.toggle('show');
            
            if (replyForm.classList.contains('show')) {
                replyForm.querySelector('.reply-input').focus();
            }
        });
    });
    
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const replyForm = this.closest('.modern-comment-reply-form');
            replyForm.classList.remove('show');
            replyForm.querySelector('.reply-input').value = '';
        });
    });
    
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const commentText = this.dataset.commentText;
            const commentItem = this.closest('.modern-comment-item, .modern-comment-reply-item');
            const articleId = commentItem.closest('.comments-section').getAttribute('data-article-id');
            
            sessionStorage.setItem('editCommentData', JSON.stringify({
                articleId: articleId,
                commentId: commentId,
                oldText: commentText
            }));
            
            const contentElement = commentItem.querySelector('.modern-comment-content');
            
            const originalContent = contentElement.innerHTML;
            contentElement.innerHTML = `
                <form class="edit-comment-form" data-comment-id="${commentId}">
                  <div class="input-group">
                    <input type="text" class="form-control edit-input" value="${commentText}" required minlength="2" maxlength="500">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-success btn-sm">Save</button>
                      <button type="button" class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                    </div>
                  </div>
                </form>
            `;
            
            contentElement.querySelector('.edit-input').focus();
        });
    });
    
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const commentItem = this.closest('.modern-comment-item, .modern-comment-reply-item');
            const articleId = commentItem.closest('.comments-section').getAttribute('data-article-id');
            const commentText = commentItem.querySelector('.modern-comment-content').textContent;
            
            sessionStorage.setItem('deleteCommentData', JSON.stringify({
                articleId: articleId,
                commentId: commentId,
                commentText: commentText
            }));
            
            if (confirm('Are you sure you want to delete this comment?')) {
                const form = document.getElementById('delete-comment-form') || createDeleteForm();
                form.querySelector('input[name="comment_id"]').value = commentId;
                form.submit();
            }
        });
    });
    
    function createDeleteForm() {
        const form = document.createElement('form');
        form.id = 'delete-comment-form';
        form.method = 'POST';
        form.action = 'deletecomment.php';
        form.style.display = 'none';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'comment_id';
        form.appendChild(input);
        
        document.body.appendChild(form);
        return form;
    }
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('cancel-edit')) {
            const form = e.target.closest('.edit-comment-form');
            const commentItem = form.closest('.modern-comment-item, .modern-comment-reply-item');
            const originalContent = form.querySelector('.edit-input').value;
            
            sessionStorage.removeItem('editCommentData');
            
            commentItem.querySelector('.modern-comment-content').innerHTML = originalContent;
        }
    });
    
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('edit-comment-form')) {
            e.preventDefault();
            const form = e.target;
            const commentId = form.dataset.commentId;
            const newContent = form.querySelector('.edit-input').value;
            
            if (newContent.length < 2 || newContent.length > 500) {
                alert('Comment must be between 2 and 500 characters.');
                return;
            }
            
            const updateForm = document.getElementById('update-comment-form') || createUpdateForm();
            updateForm.querySelector('input[name="comment_id"]').value = commentId;
            updateForm.querySelector('input[name="comment_content"]').value = newContent;
            updateForm.submit();
        }
    });
    
    function createUpdateForm() {
        const form = document.createElement('form');
        form.id = 'update-comment-form';
        form.method = 'POST';
        form.action = 'updatecomment.php';
        form.style.display = 'none';
        
        const commentIdInput = document.createElement('input');
        commentIdInput.type = 'hidden';
        commentIdInput.name = 'comment_id';
        form.appendChild(commentIdInput);
        
        const contentInput = document.createElement('input');
        contentInput.type = 'hidden';
        contentInput.name = 'comment_content';
        form.appendChild(contentInput);
        
        document.body.appendChild(form);
        return form;
    }

    // SEARCH FUNCTIONALITY
    const searchInput = document.querySelector('input[type="search"]');
    const searchButton = document.querySelector('button[type="submit"]');

    if (searchInput) {
        const performSearch = () => {
            const query = searchInput.value.trim().toLowerCase();
            const articlesGrid = document.querySelector('.articles-grid');
            const articleItems = document.querySelectorAll('.article-item');
            let foundAny = false;
            
            // Remove any existing "no results" messages
            const existingNoResult = document.getElementById('search-no-result');
            if (existingNoResult) {
                existingNoResult.remove();
            }
            
            articleItems.forEach(articleItem => {
                // Get searchable content
                const titleEl = articleItem.querySelector('.article-title a');
                const contentEl = articleItem.querySelector('.article-excerpt');
                const categoryEl = articleItem.querySelector('.article-category');
                
                const title = (titleEl?.textContent || '').toLowerCase();
                const content = (contentEl?.textContent || '').toLowerCase();
                const category = (categoryEl?.textContent || '').toLowerCase();
                
                // Check if article matches search query
                const matches = query === '' || 
                               title.includes(query) || 
                               content.includes(query) || 
                               category.includes(query);
                
                if (matches) {
                    articleItem.style.display = '';
                    foundAny = true;
                } else {
                    articleItem.style.display = 'none';
                }
            });
            
            // Show "no results" message if needed
            if (!foundAny && query !== '') {
                const noResultDiv = document.createElement('div');
                noResultDiv.id = 'search-no-result';
                noResultDiv.className = 'no-articles';
                noResultDiv.innerHTML = `
                    <i class="fas fa-search fa-3x"></i>
                    <h4>No articles found</h4>
                    <p class="text-muted">Try different keywords or check your spelling.</p>
                `;
                articlesGrid.appendChild(noResultDiv);
            }
        };
        
        // Add event listener for input (real-time search)
        searchInput.addEventListener('input', performSearch);
        
        // Add event listener for button click
        if (searchButton) {
            searchButton.addEventListener('click', (e) => {
                e.preventDefault();
                performSearch();
            });
        }
        
        // Handle form submit
        const searchForm = searchInput.closest('form');
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                performSearch();
            });
        }
    }

    // CATEGORY FILTERING FUNCTIONALITY
    const categoryLinks = document.querySelectorAll('.category-filter');

    if (categoryLinks.length > 0) {
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links
                categoryLinks.forEach(l => {
                    l.classList.remove('active', 'bg-primary', 'text-white');
                });
                
                // Add active class to clicked link
                this.classList.add('active', 'bg-primary', 'text-white');
                
                const selectedCategory = this.getAttribute('data-cat');
                const articlesGrid = document.querySelector('.articles-grid');
                const articleItems = document.querySelectorAll('.article-item');
                let foundAny = false;
                
                // Remove any existing "no results" messages
                const existingCatNoResult = document.getElementById('cat-no-result');
                if (existingCatNoResult) {
                    existingCatNoResult.remove();
                }
                
                articleItems.forEach(articleItem => {
                    if (selectedCategory === 'all') {
                        articleItem.style.display = '';
                        foundAny = true;
                        return;
                    }
                    
                    const categoryEl = articleItem.querySelector('.article-category');
                    if (categoryEl) {
                        const articleCategory = categoryEl.textContent.trim().toLowerCase();
                        
                        // Remove '#' symbol if present
                        const cleanArticleCategory = articleCategory.replace('#', '').replace(/^education$/i, 'education');
                        const cleanSelectedCategory = selectedCategory.toLowerCase();
                        
                        if (cleanArticleCategory === cleanSelectedCategory) {
                            articleItem.style.display = '';
                            foundAny = true;
                        } else {
                            articleItem.style.display = 'none';
                        }
                    } else {
                        // If article has no category, hide it when filtering (except "all")
                        articleItem.style.display = 'none';
                    }
                });
                
                // Show "no results" message for categories (except "all")
                if (!foundAny && selectedCategory !== 'all') {
                    const catNoResultDiv = document.createElement('div');
                    catNoResultDiv.id = 'cat-no-result';
                    catNoResultDiv.className = 'no-articles';
                    catNoResultDiv.innerHTML = `
                        <i class="fas fa-folder-open fa-3x"></i>
                        <h4>No articles in this category yet</h4>
                        <p class="text-muted">Check back later for new articles in "${this.textContent.trim()}".</p>
                    `;
                    articlesGrid.appendChild(catNoResultDiv);
                }
            });
        });
        
        // Auto-click "All Articles" on page load
        const allArticlesLink = document.querySelector('.category-filter[data-cat="all"]');
        if (allArticlesLink) {
            allArticlesLink.click();
        }
    }

    // Add reset filters button
    const categoriesSection = document.querySelector('.col-lg-4 .bg-white.rounded.p-4.shadow-sm.mb-4:nth-child(2)');
    if (categoriesSection) {
        const resetButton = document.createElement('button');
        resetButton.className = 'btn btn-outline-secondary btn-sm w-100 mt-3';
        resetButton.innerHTML = '<i class="fas fa-redo me-1"></i> Reset Filters';
        resetButton.addEventListener('click', function() {
            // Reset search
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reset category filter
            const allArticlesLink = document.querySelector('.category-filter[data-cat="all"]');
            if (allArticlesLink) {
                allArticlesLink.click();
            }
            
            // Show all articles
            document.querySelectorAll('.article-item').forEach(item => {
                item.style.display = '';
            });
            
            // Remove any "no results" messages
            const searchNoResult = document.getElementById('search-no-result');
            if (searchNoResult) searchNoResult.remove();
            
            const catNoResult = document.getElementById('cat-no-result');
            if (catNoResult) catNoResult.remove();
        });
        
        // Check if there's already a reset button
        if (!categoriesSection.querySelector('.btn-outline-secondary[type="button"]')) {
            categoriesSection.appendChild(resetButton);
        }
    }
    
    // Fix for Facebook reaction panel positioning
    document.addEventListener('click', function(e) {
        // Hide all reaction panels when clicking elsewhere
        if (!e.target.closest('.fb-reaction-container')) {
            document.querySelectorAll('.fb-reaction-panel').forEach(panel => {
                panel.classList.remove('show');
            });
        }
    });
    
    // Ensure reaction panels are positioned correctly on window resize
    window.addEventListener('resize', function() {
        document.querySelectorAll('.fb-reaction-panel').forEach(panel => {
            panel.classList.remove('show');
        });
    });
});
  </script>
</body>
</html>