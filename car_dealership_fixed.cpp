#include <iostream>
#include <fstream>
#include <vector>
#include <string>
#include <conio.h>
#include <ctime>
#include <iomanip>
#include <windows.h>  // تمت إضافة هذه المكتبة لاستخدام system("cls")
using namespace std;


//2. 🏗️ الهياكل الأساسية (Structs)
// هيكل السيارة - بطاقة تعريف كل سيارة

struct Car {
    string id;
    string brand;
    string model;
    double price;
    int quantity;

    // 🎯 المنشئ - يعطي قيم ابتدائية عند إنشاء سيارة جديدة
    Car() : id(""), brand(""), model(""), price(0.0), quantity(0) {}
};


// هيكل المستخدم - هوية كل مستخدم
struct User {
    string username;
    string password;
    string role;

    User() : username(""), password(""), role("user") {}
};

//هيكل عنصر الفاتورة - كل سيارة في الفاتورة
struct InvoiceItem {
    string carId;
    string brand;
    string model;
    double price;
    int quantity;
    double total;

    InvoiceItem() : carId(""), brand(""), model(""), price(0.0), quantity(0), total(0.0) {}
};

//هيكل الفاتورة - فاتورة بيع كاملة

struct Invoice {
    string invoiceId;
    string date;
    vector<InvoiceItem> items;
    double grandTotal;

    Invoice() : invoiceId(""), date(""), grandTotal(0.0) {}
};

//3  المكدس اليدوي (Stackz
// العقدة(Node) - حلقة في السلسلة
template <typename T>
class Stack {
private:
    struct Node {
        T data;
        Node* next;
        Node(T val) : data(val), next(nullptr) {}
    };
    Node* top;

public:
    Stack() : top(nullptr) {}

    ~Stack() {
        while (!isEmpty()) {
            pop();
        }
    }

    void push(T val) {
        Node* newNode = new Node(val);
        newNode->next = top;
        top = newNode;
    }

    T pop() {
        if (isEmpty()) {
            throw runtime_error("Stack is empty");
        }
        Node* temp = top;       // حفظ المؤشر للعقدة الحالية ليتم حذفها لاحقاً
        T data = top->data;      // نسخ البيانات لإرجاعها بعد الحذف
        top = top->next;        // تحريك القمة للإشارة إلى العنصر التالي
        delete temp;
        return data;
    }

    T peek() {           // دالة لإظهار العنصر الأعلى دون إزالته
        if (isEmpty()) {
            throw runtime_error("Stack is empty");
        }
        return top->data;
    }

    bool isEmpty() {                // دالة للتحقق إذا كان المكدس فارغ
        return top == nullptr;
    }
};

// 4. 📊 التعدادات والمتغيرات العامة
//أنواع الخيارات في القوائم

enum MenuOption {
    ADD_CAR = 1,
    DISPLAY_CARS,
    SEARCH_CAR,
    UPDATE_CAR,
    DELETE_CAR,
    CREATE_INVOICE,         // إنشاء فاتورة = 6
    VIEW_INVOICES,          // عرض الفواتير = 7
    UNDO_DELETE,
    EXIT_SYSTEM
};

// المتغيرات العامة - الذاكرة الرئيسية للنظام
vector<Car> cars;
vector<User> users;
vector<Invoice> invoices;
Stack<Car> deletedCarsStack;
User currentUser;



// 5. 📢 التعريفات المسبقة للدوال
void displayInvoice(const Invoice& inv);     // إعلان دالة عرض فاتورة (التنفيذ لاحقًا)
string getPassword();                         // إعلان دالة قراءة كلمة المرور مخفية الأحرف
void ensureUsersFile();                       // إعلان دالة التأكد من وجود ملف المستخدمين
void loadUsers();                             // إعلان دالة تحميل المستخدمين من الملف
void loadCars();                              // إعلان دالة تحميل السيارات من الملف
void saveCars();                              // إعلان دالة حفظ السيارات إلى الملف
void saveUsers();                             // إعلان دالة حفظ المستخدمين إلى الملف
void loadInvoices();                          // إعلان دالة تحميل الفواتير من الملف
void saveInvoices();                          // إعلان دالة حفظ الفواتير إلى الملف
bool login();                                 // إعلان دالة تسجيل الدخول (ترجع true عند النجاح)
void registerUser();                          // إعلان دالة تسجيل مستخدم جديد
void addCar();                                // إعلان دالة إضافة سيارة
void displayCars();                           // إعلان دالة عرض السيارات
void searchCar();                             // إعلان دالة بحث سيارة
void updateCar();                             // إعلان دالة تحديث سيارة
void deleteCar();                             // إعلان دالة حذف سيارة
void undoDelete();                            // إعلان دالة للتراجع عن الحذف
void createInvoice();                         // إعلان دالة إنشاء فاتورة
void viewInvoices();                          // إعلان دالة عرض كل الفواتير
void adminMenu();                             // إعلان دالة قائمة المدير
void userMenu();                              // إعلان دالة قائمة المستخدم العادي
void authMenu();                              // إعلان دالة قائمة المصادقة (دخول/تسجيل)
void clearScreen();  // تمت إضافة دالة مسح الشاشة  // إعلان دالة مسح الشاشة

// 6================= دالة مسح الشاشة =================
void clearScreen() {
    system("cls");
}

// 7. 🔐 دالة إدخال كلمة المرور

string getPassword() {
    string pass;
    char ch;
    while ((ch = _getch()) != 13) {
        if (ch == 8 && !pass.empty()) {
            cout << "\b \b";
            pass.pop_back();       //نحذف آخر حرف فعلاً من كلمة السر المخزنة في المتغير pass.
            cout << '*';
        }
        else if (ch != 8) {
            pass.push_back(ch);     
        }
    }
    cout << endl;
    return pass;
}

// 8. 💾 دوال إدارة الملفات
// التأكد من وجود ملف المستخدمين 

void ensureUsersFile() {
    ifstream f("users.txt");
    if (!f) {
        ofstream ff("users.txt");
        ff << "admin 1234 admin\n";
        ff << "user1 pass1 user\n";
        cout << ">> users.txt created with default users.\n";
    }
}

// تحميل بيانات المستخدمين من الملف
void loadUsers() {          //وظيفتها: قراءة جميع المستخدمين من ملف users.txt وتخزينهم داخل المتغير العالمي
    users.clear();
    ifstream f("users.txt");
    User u;
    while (f >> u.username >> u.password >> u.role) {
        users.push_back(u);         //استمر في القراءة طالما هناك بيانات.
    }
}

// تحميل بيانات السيارات من الملف
void loadCars() {
    cars.clear();
    ifstream f("cars.txt");
    if (!f) return;

    Car c;
    string priceStr, quantityStr;           //متغيرين priceStr و quantityStr لقراءة السعر والكمية كنصوص (سنتحولها لأرقام لاحقًا)
    while (getline(f, c.id, ',') &&
        getline(f, c.brand, ',') &&
        getline(f, c.model, ',') &&
        getline(f, priceStr, ',') &&
        getline(f, quantityStr)) {
        c.price = stod(priceStr);       //💡 نحول السعر من نص إلى رقم عشري (stod)
        c.quantity = stoi(quantityStr); //ونحول الكمية من نص إلى عدد صحيح (stoi)
        cars.push_back(c);
    }
}

// حفظ بيانات السيارات في الملف

void saveCars() {
    ofstream f("cars.txt");
    for (auto c : cars) {
        f << c.id << "," << c.brand << "," << c.model << ","
            << c.price << "," << c.quantity << endl;
    }
}

void saveUsers() {
    ofstream f("users.txt");
    for (auto u : users) {
        f << u.username << " " << u.password << " " << u.role << endl;
    }
}


// تحميل الفواتير من الملف
void loadInvoices() {
    invoices.clear();
    ifstream f("invoices.txt");
    if (!f) return;

    Invoice inv;
    string line;
    while (getline(f, inv.invoiceId)) {     //نقرأ أول 3 أسطر من كل فاتورة:
        getline(f, inv.date);
        string itemCountStr;
        getline(f, itemCountStr);
        int itemCount = stoi(itemCountStr);  //ثم نحول العدد من نص إلى رقم.

        inv.items.clear();      //نبدأ نقرأ كل بند داخل الفاتورة، كل بند يمثل سيارة تم بيعها.
        for (int i = 0; i < itemCount; i++) {
            InvoiceItem item;
            getline(f, item.carId, ',');
            getline(f, item.brand, ',');
            getline(f, item.model, ',');

            string priceStr, quantityStr, totalStr;
            getline(f, priceStr, ',');
            getline(f, quantityStr, ',');
            getline(f, totalStr);

            item.price = stod(priceStr); // نحول النصوص إلى أرقام حقيقية.
            item.quantity = stoi(quantityStr);
            item.total = stod(totalStr);

            inv.items.push_back(item);      //نضيف العنصر إلى قائمة العناصر داخل الفاتورة.
        }

        string totalStr;        //نقرأ المجموع الكلي للفاتورة ونضيف الفاتورة إلى قائمة الفواتير.
        getline(f, totalStr);
        inv.grandTotal = stod(totalStr);
        invoices.push_back(inv);

        // هذا السطر يقرأ السطر الفارغ بين الفواتير (يفصل الفواتير عن بعضها).
        getline(f, line);
    }
}


//حفظ الفواتير
void saveInvoices() {
    ofstream f("invoices.txt");
    for (auto inv : invoices) {
        f << inv.invoiceId << endl;
        f << inv.date << endl;
        f << inv.items.size() << endl;

        for (auto item : inv.items) {
            f << item.carId << "," << item.brand << "," << item.model << ","
                << item.price << "," << item.quantity << "," << item.total << endl;
        }

        f << inv.grandTotal << endl;
        f << endl; // نكتب المجموع النهائي، ثم سطر فارغ للفصل بين الفواتير.
    }
}

//9. 🔐 دوال المصادقة والتسجيل
bool login() {
    string username, password;
    cout << "Enter username: ";
    cin >> username;
    cout << "Enter password: ";
    password = getPassword();

    for (auto u : users) {
        if (u.username == username && u.password == password) {
            currentUser = u;
            return true;
        }
    }
    return false;
}

// تسجيل مستخدم جديد
void registerUser() {
    User newUser;
    cout << "Enter new username: ";
    cin >> newUser.username;

    // Check if username exists
    for (auto u : users) {
        if (u.username == newUser.username) {
            cout << ">> Username already exists!\n";
            Sleep(2000);  // انتظار 2 ثانية قبل مسح الشاشة
            clearScreen();
            return;
        }
    }

    cout << "Enter password: ";
    newUser.password = getPassword();
    newUser.role = "user"; //  نعين الصلاحية كمستخدم عادي

    users.push_back(newUser);       //نضيف المستخدم الجديد إلى قائمة المستخدمين في الذاكرة
    saveUsers();
    cout << ">> Registration successful! You can now login.\n";
    Sleep(2000);  // انتظار 2 ثانية قبل مسح الشاشة
    clearScreen();
}

// 10. 🚗 دوال إدارة السيارات

void addCar() {
    if (currentUser.role != "admin") {      // نتحقق من صلاحية المستخدم
        cout << ">> Access denied! Admin only.\n";
        Sleep(2000);
        clearScreen();
        return;
    }

    Car c;
    cin.ignore();
    cout << "Enter Car ID: "; getline(cin, c.id);
    cout << "Enter Brand: "; getline(cin, c.brand);
    cout << "Enter Model: "; getline(cin, c.model);
    cout << "Enter Price: "; cin >> c.price;
    cout << "Enter Quantity: "; cin >> c.quantity;

    cars.push_back(c);
    saveCars();
    cout << ">> Car added successfully!\n";
    Sleep(2000);  // انتظار 2 ثانية قبل مسح الشاشة
    clearScreen();
}

// عرض جميع السيارات 

void displayCars() {
    if (cars.empty()) {
        cout << ">> No cars found in inventory!\n";
        cout << "Press any key to continue...";
        (void)_getch();        //ننتظر ضغط أي مفتاح
        clearScreen();
        return;
    }

    cout << "\n" << string(80, '=') << "\n";
    cout << "                               CAR INVENTORY\n";
    cout << string(80, '=') << "\n";
    cout << left << setw(15) << "ID" << setw(15) << "Brand"
        << setw(20) << "Model" << setw(12) << "Price"
        << setw(10) << "Quantity" << endl;
    cout << string(80, '-') << "\n";

    for (auto c : cars) {
        cout << left << setw(15) << c.id << setw(15) << c.brand
            << setw(20) << c.model << setw(12) << fixed << setprecision(2) << c.price
            << setw(10) << c.quantity << endl;
    }
    cout << string(80, '=') << "\n";

    cout << "Press any key to continue...";
    (void)_getch();
    clearScreen();
}

// البحث عن سيارة
void searchCar() {
    string id;
    cin.ignore();
    cout << "Enter Car ID to search: "; getline(cin, id);

    for (auto c : cars) {
        if (c.id == id) {
            cout << "\n>> Car Found!\n";
            cout << "ID: " << c.id << " | Brand: " << c.brand
                << " | Model: " << c.model << " | Price: " << c.price
                << " | Quantity: " << c.quantity << endl;
            cout << "Press any key to continue...";
            (void)_getch();
            clearScreen();
            return;
        }
    }

    cout << ">> Car not found!\n";
    Sleep(2000);
    clearScreen();
}

// // ?? دالة تعديل بيانات سيارة
void updateCar() {
    if (currentUser.role != "admin") {
        cout << ">> Access denied! Admin only.\n";
        Sleep(2000);
        clearScreen();
        return;
    }

    string id;
    cin.ignore();
    cout << "Enter Car ID to update: "; getline(cin, id);

    for (auto& c : cars) {
        if (c.id == id) {
            cout << "Enter new Brand: "; getline(cin, c.brand);
            cout << "Enter new Model: "; getline(cin, c.model);
            cout << "Enter new Price: "; cin >> c.price;
            cout << "Enter new Quantity: "; cin >> c.quantity;

            saveCars();
            cout << ">> Car updated successfully!\n";
            Sleep(2000);
            clearScreen();
            return;
        }
    }
    cout << ">> Car not found!\n";
    Sleep(2000);
    clearScreen();
}

// حذف سيارة
void deleteCar() {
    if (currentUser.role != "admin") {
        cout << ">> Access denied! Admin only.\n";
        Sleep(2000);
        clearScreen();
        return;
    }
    
       string id;         
    cin.ignore();       // ?? ننظف buffer الإدخال
    cout << "Enter Car ID to delete: ";
    getline(cin, id);   // ?? نقرأ رقم السيارة
    for (auto it = cars.begin(); it != cars.end(); ++it) {  // ?? نبحث عن السيارة
        if (it->id == id) {              // ? إذا وجدنا السيارة
            deletedCarsStack.push(*it);  // ??? نحفظ السيارة في مكدس المحذوفات
            cars.erase(it);              // ? نحذف السيارة من القائمة
            saveCars();                  // ?? نحفظ التغييرات في الملف
            cout << ">> Car deleted successfully!\n";  
            Sleep(2000);         
            clearScreen();       
            return;             
        }
    }
    cout << ">> Car not found!\n";
    Sleep(2000);
    clearScreen();
}

//التراجع عن الحذف
void undoDelete() {
    if (currentUser.role != "admin") {
        cout << ">> Access denied! Admin only.\n";
        Sleep(2000);
        clearScreen();
        return;
    }

    if (deletedCarsStack.isEmpty()) {
        cout << ">> No cars to restore!\n";
        Sleep(2000);
        clearScreen();
        return;
    }

    Car restoredCar = deletedCarsStack.pop();               // ?? نستعيد آخر سيارة محذوفة
    cars.push_back(restoredCar);
    saveCars();
    cout << ">> Car restored successfully!\n";            // ?? نعلم المستخدم
    cout << "Restored Car - ID: " << restoredCar.id
        << " | Brand: " << restoredCar.brand
        << " | Model: " << restoredCar.model << endl;
    cout << "Press any key to continue...";
    (void)_getch();   
    clearScreen();
}


// 11. 🧾 دوال الفواتير
string generateInvoiceId() {
    time_t now = time(0);
    tm ltm;
    localtime_s(&ltm, &now);        // ?? نحول الوقت لبنية
    string id = "INV_" + to_string(1900 + ltm.tm_year) +
        to_string(1 + ltm.tm_mon) +
        to_string(ltm.tm_mday) + "_" +
        to_string(invoices.size() + 1);
    return id;
}

// الحصول على التاريخ الحالي
string getCurrentDate() {
    time_t now = time(0);
    char buf[80];        // ?? مصفوفة لتخزين النص
    tm ltm;
    localtime_s(&ltm, &now);
    strftime(buf, sizeof(buf), "%Y-%m-%d %H:%M:%S", &ltm);
    return string(buf);
}

// ?? دالة عرض فاتورة
// الدوال التي تأخذ باراميتر - دالة واحدة فقط تأخذ فاتورة كباراميتر وتعرضها
void displayInvoice(const Invoice& inv) {
    cout << "\n" << string(60, '=') << "\n";
    cout << "                      CAR DEALERSHIP INVOICE\n";
    cout << string(60, '=') << "\n";
    cout << "Invoice ID: " << inv.invoiceId << endl;
    cout << "Date: " << inv.date << endl;
    cout << string(60, '-') << "\n";
    cout << left << setw(15) << "Car ID" << setw(15) << "Brand"
        << setw(15) << "Model" << setw(8) << "Qty"
        << setw(10) << "Price" << setw(12) << "Total" << endl;
    cout << string(60, '-') << "\n";

    for (auto item : inv.items) {          // ?? لكل عنصر في الفاتورة
        cout << left << setw(15) << item.carId << setw(15) << item.brand
            << setw(15) << item.model << setw(8) << item.quantity
            << setw(10) << fixed << setprecision(2) << item.price
            << setw(12) << item.total << endl;
    }

    cout << string(60, '-') << "\n";
    cout << right << setw(50) << "Grand Total: $" << inv.grandTotal << endl;        // ?? نعرض المجموع الكلي
    cout << string(60, '=') << "\n";
}

// إنشاء فاتورة جديدة
void createInvoice() {
    Invoice newInvoice;
    newInvoice.invoiceId = generateInvoiceId();     // ?? نولد رقم فاتورة
    newInvoice.date = getCurrentDate();             // ?? نأخذ التاريخ الحالي
    newInvoice.grandTotal = 0;                   // ?? نبدأ المجموع من الصفر
    char addMore = 'y';      // ?? متغير للاستمرار في الإضافة
    while (addMore == 'y' || addMore == 'Y') {   // ?? طالما المستخدم يريد إضافة
        InvoiceItem item;    // ?? كائن لعنصر الفاتورة
        string carId;        // ?? متغير لرقم السيارة
        int quantity;        // ?? متغير للكمية
        cout << "Enter Car ID: ";    // ?? نطلب رقم السيارة
        cin >> carId;                // ?? نقرأ رقم السيارة
        Car* foundCar = nullptr;     // ?? مؤشر للسيارة
        for (auto& c : cars) {       // ?? نبحث عن السيارة
            if (c.id == carId) {     // ? إذا وجدنا السيارة
                foundCar = &c;       // ?? نحفظ مؤشر السيارة
                break;               // ?? نكسر الحلقة
            }
        }

        if (!foundCar) {
            cout << ">> Car not found!\n";
            Sleep(2000);
            clearScreen();
            continue;
        }

        cout << "Enter quantity: ";
        cin >> quantity;

        if (quantity > foundCar->quantity) {    // ?? نقرأ الكمية
            cout << ">> Not enough quantity in stock! Available: " << foundCar->quantity << endl;  // ?? نعلم المستخدم
            Sleep(2000);
            clearScreen();
            continue;
        }

        //تحديث كمية السيارة
        foundCar->quantity -= quantity;     // ?? ننقص الكمية من المخزون

        // انشاء عنصر فاتوره 
        item.carId = foundCar->id;
        item.brand = foundCar->brand;
        item.model = foundCar->model;
        item.price = foundCar->price;
        item.quantity = quantity;
        item.total = foundCar->price * quantity;    // ?? نحسب المجموع

        newInvoice.items.push_back(item);         // ? نضيف العنصر للفاتورة
        newInvoice.grandTotal += item.total;      // ?? نضيف للمجموع الكلي
        cout << "Add another car? (y/n): ";
        cin >> addMore;
        clearScreen();
    }

    if (!newInvoice.items.empty()) {        // ? إذا كانت الفاتورة تحتوي على عناصر
        invoices.push_back(newInvoice);     // ? نضيف الفاتورة للقائمة
        saveCars();                         // ?? نحفظ تحديث كميات السيارات
        saveInvoices();                     // ?? نحفظ الفاتورة
        clearScreen();                     // ?? نمسح الشاشة
        displayInvoice(newInvoice);        // ?? نعرض الفاتورة
        cout << "Press any key to continue...";
        (void)_getch();
        clearScreen();
    }
}

// ?? دالة عرض جميع الفواتير
void viewInvoices() {
    if (invoices.empty()) {
        cout << ">> No invoices found!\n";
        Sleep(2000);
        clearScreen();
        return;
    }

    for (auto inv : invoices) {
        displayInvoice(inv);
        cout << "Press any key to view next invoice...";
        (void)_getch();
        clearScreen();
    }
}

// 12. 🎮 القوائم الرئيسية قائمة المدير

void adminMenu() {
    int choice;
    do {
        clearScreen();  // مسح الشاشة قبل عرض القائمة
        cout << "\n" << string(50, '=') << "\n";
        cout << "          ADMIN DASHBOARD - Car Dealership\n";
        cout << string(50, '=') << "\n";
        cout << "1. Add Car\n2. Show Cars\n3. Search Car\n4. Update Car\n";
        cout << "5. Delete Car\n6. Create Invoice\n7. View Invoices\n";
        cout << "8. Undo Delete\n9. Exit\n";
        cout << string(50, '-') << "\n";
        cout << "Choice: ";
        cin >> choice;

        switch (choice) {           // ?? ننفذ الوظيفة حسب الاختيار
        case ADD_CAR: addCar(); break;
        case DISPLAY_CARS: displayCars(); break;
        case SEARCH_CAR: searchCar(); break;
        case UPDATE_CAR: updateCar(); break;
        case DELETE_CAR: deleteCar(); break;
        case CREATE_INVOICE: createInvoice(); break;
        case VIEW_INVOICES: viewInvoices(); break;
        case UNDO_DELETE: undoDelete(); break;
        case EXIT_SYSTEM:
            cout << "Exiting...\n";
            Sleep(1000);
            clearScreen();
            break;
        default:         // ? اختيار غير صحيح
            cout << ">> Invalid choice!\n";
            Sleep(2000);
            clearScreen();
        }
    } while (choice != EXIT_SYSTEM);
}

// ?? دالة القائمة الرئيسية للمستخدم
void userMenu() {
    int choice;
    do {
        clearScreen();  // مسح الشاشة قبل عرض القائمة
        cout << "\n" << string(50, '=') << "\n";
        cout << "          USER DASHBOARD - Car Dealership\n";
        cout << string(50, '=') << "\n";
        cout << "1. Show Cars\n2. Search Car\n3. Create Invoice\n";
        cout << "4. View Invoices\n5. Exit\n";
        cout << string(50, '-') << "\n";
        cout << "Choice: ";
        cin >> choice;

        switch (choice) {
        case 1: displayCars(); break;
        case 2: searchCar(); break;
        case 3: createInvoice(); break;
        case 4: viewInvoices(); break;
        case 5:
            cout << "Exiting...\n";
            Sleep(1000);
            clearScreen();
            break;
        default:
            cout << ">> Invalid choice!\n";
            Sleep(2000);
            clearScreen();
        }
    } while (choice != 5);
}

void authMenu() {
    int choice;
    do {
        clearScreen();  // مسح الشاشة قبل عرض القائمة
        cout << "\n" << string(40, '=') << "\n";
        cout << "     CAR DEALERSHIP MANAGEMENT\n";
        cout << string(40, '=') << "\n";
        cout << "1. Login\n2. Register\n3. Exit\n";
        cout << string(40, '-') << "\n";
        cout << "Choice: ";
        cin >> choice;

        switch (choice) {
        case 1:
            if (login()) {
                cout << ">> Login successful! Welcome " << currentUser.username << "!\n";
                Sleep(2000);
                clearScreen();
                if (currentUser.role == "admin") {
                    adminMenu();
                }
                else {
                    userMenu();
                }
            }
            else {
                cout << ">> Invalid username or password!\n";
                Sleep(2000);
                clearScreen();
            }
            break;
        case 2:
            registerUser();
            break;
        case 3:
            cout << "Exiting...\n";
            Sleep(1000);
            clearScreen();
            break;
        default:
            cout << ">> Invalid choice!\n";
            Sleep(2000);
            clearScreen();
        }
    } while (choice != 3);
}

// 13. 🏁 الدالة الرئيسية
int main() {
    ensureUsersFile();
    loadUsers();
    loadCars();
    loadInvoices();

    authMenu();     // ?? نبدأ بقائمة المصادقة
    return 0;
}