### 1. Zemin katta bulunan bir yolcu, 5. kata gitmek için asansör çağırdığında hangi asansör gelir? Hangi algoritmayı kullandınız?

Zemin kattan en yakın veya boşta olan, ya da zemin kata doğru hareket eden asansör seçilir. Kullanılan algoritma asansör hareketine uygun şekilde uyarlanmış olan "SSTF(shortest seek time first)" algoritmasıdır.

### 2.Bu kodu yazmak için hangi design pattern’ı kullanırsınız ve neden?

Biz Gözlemci (Observer) ve Durum (State) tasarım desenlerini kullandık:

- **Gözlemci Deseni (Observer Pattern)**: Asansör sisteminin tüm asansörlerini durum değişiklikleri konusunda bilgilendirmesine izin vermek için kullanıldı. Bu, sistemi genişletilebilir ve yönetilebilir hale getirmeye yardımcı olur.

- **Durum Deseni (State Pattern)**: Her bir asansörün mevcut durumuna (Yukarı Hareket, Aşağı Hareket, Bekleme) bağlı olarak değişen davranışı kapsamak için kullanıldı. Bu, her bir asansörün durum yönetimini temiz hale getirir.

### 3. Genişleyebilir bir yapıda bu kodu geliştirmek için ihtiyaç duyacağınız fonksiyon model ve Class sınıfları neler olmalıdır? Hangi parametreleri almalıdır?

- **ElevatorService (Observable olan service sınıfı)**: Genel asansör sisteminin yönetimini yapar.
  - `callElevator(int $currentFloor, int $targetFloor)`: Bir asansör çağırır.
  - `notify()`: Tüm observerları bir durum değişikliği konusunda bilgilendirir.
  - `getSelectedElevator()`: Şu an seçili olan asansörü döndürür.

- **Elevator (Observer'a sahip olan model)**
  - `move()`: Asansörü hareket ettirir.
  - `updateState(Observable $observable)`: Gözlemci içindeki değişikliklere dayanarak asansörün durumunu günceller.
  
- **ElevatorState (Statelerin kullanımını sınırlamak amacıyla kullanılan Interface)**
  - `move(Elevator $elevator)`: Asansörün mevcut durumuna göre nasıl hareket etmesi gerektiğini belirler.

- **ElevatorStateMovingUp (Elevator State Interface'ını impelemente eden Somut Sınıf)**
- **ElevatorStateMovingDown (Elevator State Interface'ını impelemente eden Somut Sınıf)**
- **ElevatorStateIdle (Elevator State Interface'ını impelemente eden Somut Sınıf)**

### 4. Asansörler veritabanından hangi asansörün geleceğini sonuç olarak dönecek bir SQL kodu yazar mısınız?

Projemizde hangi asansörün kullanılacağını seçen algoritmayı ElevatorService dosyasında yönettik fakat bunu sql'de yönetmek isteseydik;

SELECT name FROM elevators
WHERE (direction = 'up' AND current_floor <= :current_floor AND target_floor >= :target_floor)
OR (direction = 'down' AND current_floor >= :current_floor AND target_floor <= :target_floor)
ORDER BY ABS(current_floor - :current_floor)
LIMIT 1;

Sorgusunu yazabilirdik, bu yöntemle oluşturduğumuz koşullar şu şekilde;

İlk koşul: direction = 'up' AND current_floor <= :current_floor AND target_floor >= :target_floor: Bu koşul, asansörün yukarı yönde hareket ettiği ve hedef kat aralığına gitmek istediği durumu kontrol eder. current_floor, mevcut katı belirtir ve target_floor, hedef katı belirtir. Bu koşul, asansörün belli bir aralığı hedeflediğini belirtir.

İkinci koşul: direction = 'down' AND current_floor >= :current_floor AND target_floor <= :target_floor: Bu koşul, asansörün aşağı yönde hareket ettiği ve hedef kat aralığına gitmek istediği durumu kontrol eder. Yine current_floor ve target_floor değişkenleri kullanılır.

ORDER BY ABS(current_floor - :current_floor): Bu bölüm, sorgu sonucunda bulunan asansörlerin sıralamasını belirler. Asansörlerin mevcut katları ile belirtilen :current_floor arasındaki farkın mutlak değerine göre sıralanır. Bu, asansörün mevcut katı ile istenen kat arasındaki en küçük farka göre sıralanmalarını sağlar. Varsayılan haliyle ASC olarak sıralanır. 

LIMIT 1: Bu bölüm, sorgu sonucunda yalnızca bir asansörün seçilmesini sağlar. Yani, en uygun asansörü bulduğunuzda sadece bu asansörün adını alırsınız.



### 5. Asansörler için rezervasyon ve kuyruk sistemi geliştirmek istersek uygun bir tablo oluşturur musunuz?

Rezervasyon ve sıra sistemi oluşturmak için, `elevator_queue` adında yeni bir tablo oluşturulabilir. Bu tablonun aşağıdaki sütunlara sahip olması gerekebilir:

- `id`: isteğe bağlı olarak otomatik oluşan benzersiz kayıt numarası
- `elevator_id`: Asansörü referansı olan foreign key
- `current_floor`: Asansörün çağrıldığı kat
- `target_floor`: Asansörün gitmesi gereken kat
- `status`: Enum ('bekliyor', 'devam ediyor', 'tamamlandı')
- `priority`: Sıra içindeki sırayı belirlemek için gereken alan
- `created_at`: timestamp
- `updated_at`: timestamp

Bu, sıra queue yönetimini etkili bir şekilde yapmamıza olanak tanır.
