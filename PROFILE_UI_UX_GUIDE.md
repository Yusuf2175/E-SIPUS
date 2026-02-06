# Panduan Profile UI/UX dengan Sidebar

## Fitur yang Diupdate

### ✅ **Profile Page dengan Sidebar**

- Menggunakan layout dashboard yang sama dengan sidebar
- Sidebar menu berbeda berdasarkan role user
- Profile page yang responsive dan modern
- Grid layout untuk form sections

### ✅ **Role-based Sidebar Menu**

#### **Admin Profile**

- Dashboard
- **User Management:**
    - Kelola Users
    - Permintaan Role (dengan badge notifikasi)
- Profile (aktif)

#### **Petugas Profile**

- Dashboard
- **Data Management:**
    - Kelola Buku
    - Kelola Anggota
- Profile (aktif)

#### **User Profile**

- Dashboard
- Profile (aktif)
- Request Role (jika role = user)
- Bantuan

### ✅ **Profile Header Section**

- Avatar dengan gradient berdasarkan role
- Nama dan email user
- Badge role dengan warna sesuai
- Tanggal bergabung
- Responsive design

### ✅ **Form Sections yang Diupdate**

#### **1. Update Profile Information**

- Icon header dengan background berwarna
- Form styling yang konsisten
- Role display (read-only)
- Success message dengan auto-hide
- Email verification notice

#### **2. Update Password**

- Icon header dengan background hijau
- Tips password aman dalam info box
- Form validation yang jelas
- Placeholder text yang helpful
- Success feedback

#### **3. Delete Account**

- Warning section dengan icon peringatan
- Modal konfirmasi yang aman
- Custom modal dengan Alpine.js
- Styling yang konsisten dengan theme

## Struktur File yang Diupdate

### **Main Profile Page**

- `resources/views/profile/edit.blade.php` - Layout utama dengan sidebar

### **Profile Partials**

- `resources/views/profile/partials/update-profile-information-form.blade.php`
- `resources/views/profile/partials/update-password-form.blade.php`
- `resources/views/profile/partials/delete-user-form.blade.php`

## Design Features

### **Color Scheme per Role**

- **Admin**: Red gradient (red-500 to pink-500)
- **Petugas**: Green gradient (green-500 to teal-500)
- **User**: Purple gradient (purple-500 to blue-500)

### **Layout Structure**

```
┌─────────────────────────────────────────┐
│ Sidebar │ Profile Header (Avatar+Info)  │
│         ├─────────────────────────────────┤
│ Menu    │ Grid Layout:                   │
│ Items   │ ┌─────────────┬─────────────┐  │
│         │ │ Profile     │ Password    │  │
│         │ │ Info Form   │ Update Form │  │
│         │ └─────────────┴─────────────┘  │
│         │ ┌─────────────────────────────┐ │
│         │ │ Delete Account Section      │ │
│         │ └─────────────────────────────┘ │
└─────────────────────────────────────────┘
```

### **Form Styling**

- Consistent input styling dengan focus states
- Icon headers untuk setiap section
- Color-coded buttons (blue, green, red)
- Proper spacing dan typography
- Error handling yang jelas

### **Interactive Elements**

- Hover effects pada sidebar menu
- Focus states pada form inputs
- Smooth transitions
- Auto-hide success messages
- Modal animations

## Responsive Design

### **Desktop (lg+)**

- 2-column grid untuk profile dan password forms
- Full sidebar visibility
- Spacious layout dengan proper margins

### **Mobile (sm-)**

- Single column layout
- Collapsible sidebar dengan overlay
- Touch-friendly form elements
- Optimized spacing

## Form Features

### **Profile Information Form**

- Name dan email fields
- Role display (read-only dengan badge)
- Email verification status
- Success feedback
- Form validation

### **Password Update Form**

- Current password field
- New password field
- Password confirmation
- Security tips dalam info box
- Strength requirements

### **Delete Account Form**

- Warning section dengan visual alert
- Modal confirmation
- Password confirmation required
- Safe deletion process
- Custom Alpine.js modal

## Security Features

### ✅ **Form Protection**

- CSRF tokens pada semua forms
- Password confirmation untuk delete
- Proper validation rules
- Error message handling

### ✅ **User Experience**

- Clear feedback messages
- Loading states (bisa ditambahkan)
- Confirmation dialogs
- Auto-hide success messages

## Customization Options

### **Mengubah Warna Role**

Edit di profile header section:

```html
@if(Auth::user()->isAdmin())
<div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-500 ...">
    @elseif(Auth::user()->isPetugas())
    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 ...">
        @else
        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 ...">
            @endif
        </div>
    </div>
</div>
```

### **Menambah Field Baru**

Tambahkan di form profile information:

```html
<div>
    <label for="new_field" class="block text-sm font-medium text-gray-700 mb-2"
        >Field Baru</label
    >
    <input
        id="new_field"
        name="new_field"
        type="text"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
    />
</div>
```

### **Mengubah Layout Grid**

Edit grid classes di main content:

```html
<!-- 2 kolom -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- 3 kolom -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
</div>
```

## JavaScript Features

### **Alpine.js Components**

- Auto-hide success messages
- Modal management
- Form interactions
- Sidebar toggle (mobile)

### **Success Message Auto-hide**

```javascript
x-data="{ show: true }"
x-show="show"
x-transition
x-init="setTimeout(() => show = false, 3000)"
```

### **Modal Management**

```javascript
x-data="{ show: false }"
x-on:open-modal.window="$event.detail == 'modal-name' ? show = true : null"
x-on:close.stop="show = false"
```

## Accessibility Features

### ✅ **Form Accessibility**

- Proper label associations
- Focus management
- Keyboard navigation
- Screen reader friendly
- Error announcements

### ✅ **Visual Accessibility**

- High contrast colors
- Clear typography
- Sufficient spacing
- Icon + text combinations
- Color-blind friendly

## Performance Optimizations

### **Loading Performance**

- Minimal JavaScript dependencies
- Efficient CSS classes
- Optimized images (avatar placeholders)
- Fast form submissions

### **User Experience**

- Instant feedback
- Smooth animations
- Progressive enhancement
- Graceful degradation

## Testing Checklist

### ✅ **Functionality**

- [ ] Profile update works correctly
- [ ] Password change works correctly
- [ ] Account deletion works with confirmation
- [ ] Email verification flow works
- [ ] Form validation displays properly
- [ ] Success messages appear and auto-hide

### ✅ **UI/UX**

- [ ] Sidebar shows correct menu for each role
- [ ] Profile header displays correct role colors
- [ ] Forms are responsive on all screen sizes
- [ ] Modal opens and closes properly
- [ ] Hover states work on interactive elements
- [ ] Focus states are visible and accessible

### ✅ **Security**

- [ ] CSRF protection active on all forms
- [ ] Password confirmation required for deletion
- [ ] Proper validation on all inputs
- [ ] Error messages don't expose sensitive info

---

**Profile page sekarang memiliki UI/UX yang konsisten dengan dashboard dan sidebar yang rapi untuk setiap role!** 🎉

Setiap role memiliki experience yang optimal dengan menu sidebar yang sesuai dan form profile yang modern dan user-friendly.
