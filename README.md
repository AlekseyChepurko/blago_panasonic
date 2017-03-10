# Исправления проекта для компании благо
 Адрес, по которому находится проект: [Благо](https://online.skblago.ru/panasonic/app.html)

## Пример запроса:

Для тестов можно выбирать поле "Cat" одним из следующих вариантов:

TEST1
TEST2
TEST3
TEST4
TEST5
TEST6
TEST7
TEST8

```
 {  
   "Head":{  
      "CompanyCODE":"panasonic",
      "OrderNum":"106016",
      "LName":"Фамилия",
      "FName":"Имя",
      "PName":"Отчество",
      "Telephone":"+7 (917) 000-00-00",
      "Email":"mail@mail.com",
      "Locality":"Белогорск"
   },
   "Sales":[  
      {  
         "Brand":"Tefal",
         "Cat":"TEST2",
         "ModelName":"Utug",
         "Price":"100001",
         "Warranty":"12",
         "Other":"Others...."
      },
      {  
         "Brand":"Tefal",
         "Cat":"TEST2",
         "ModelName":"Utug",
         "Price":"100001",
         "Warranty":"12",
         "Other":"Others...."
      },
      {  
         "Brand":"Tefal",
         "Cat":"TEST6",
         "ModelName":"Пылесос",
         "Price":"10001",
         "Warranty":"12",
         "Other":"Others...."
      }
   ]
}

```