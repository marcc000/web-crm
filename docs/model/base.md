Il modulo base del modello riguarda le anagrafiche di clienti, prospect, utenti e dati vari di categorizzazione e parametrizzazione, principalmente importati dall'erp.

Molto spesso sono presenti riferimenti a categorie o più in generale a parametri di tipo key-value quindi ho creato una entità unica per gestirli, è identificata da uno scope, cioè il tipo di categoria e una key per ogni possibile valore, opzionalmente le categorie possono essere strutturate gerarchicamente quindi una categoria può essere subordinata ad un altra.

CATEGORY_SCOPE (
    id int PK,
    description string nullable,
    FK parent REFERENCES CATEGORY_SCOPE.id nullable
)

CATEGORY (
    id int PK,
    key string,
    description string nullable,
    FK scope REFERENCES CATEGORY_SCOPE.id
)

Gli indirizzi sono suddivisi in paese, provincia e cap. Solo per i clienti ho creato l'entità separata indirizzo di consegna che estende l'indirizzo generico con informazioni logistiche.

WEEKDAY (
    id int PK,
    name string
)

WEEKDAY_AVAILABILITY (
    id int PK,
    FK address REFERENCES DELIVERY_ADDRESS.id,
    FK day REFERENCES WEEKDAY.id
)

CAP (
    id int PK,
    key string,
    city string,
    province_name string,
    provinceISO string,
)

ADDRESS (
    id int PK,
    erpID string,
    fullAddress string,
    active boolean,
    description string nullable,
    FK cap REFERENCES CAP.id,
    FK owner REFERENCES PARTNER.id
)

DELIVERY_ADDRESS (
    id int PK,
    instructions string nullable,
    FK address REFERENCES ADDRESS.id
)

Per prospect,agenti e clienti, dato che condividono la maggior parte delle informazioni, ho definito l'entità partner, questa in futuro può essere estesa per rappresentare anche altre figure in rapporto all'azienda.

PARTNER (
    id int PK,
    erpID string,
    businessName string,
    vatNumber string nullable,
    taxID string nullable,
    PEC string nullable,
    FK default_address REFERENCES ADDRESS.id,
    FK default_contact REFERENCES CONTACT.id
)

CUSTOMER (
    id int PK,
    active boolean,
    exported boolean,
    FK partner REFERENCES PARTNER.id,
    FK default_delivery_address REFERENCES DELIVERY_ADDRESS.id
)

PROSPECT (
    id int PK,
    FK partner REFERENCES PARTNER.id
)

COMPANY_INFO (
    id int PK,
    content string,
    FK owner REFERENCES ADDRESS.id,
    FK type REFERENCES CATEGORY.id
)

Una parte rilevante di un CRM riguarda la gestione dei contatti, ossia delle persone fisiche, quindi ho creato l'entità generica contact, associata opzionalmente a uno o più partner, la loro mansione all'interno dell'azienda e i vari recapiti.

CONTACT (
    id int PK,
    erpID string,
    name string,
    surname string
)

CONTACT_PARTNER (
    id int PK,
    FK partner REFERENCES PARTNER.id,
    FK job REFERENCES CATEGORY.id nullable,
    FK address REFERENCES ADDRESS.id nullable
)

CONTACT_INFO (
    id int PK,
    content string,
    FK owner REFERENCES CONTACT.id,
    FK type REFERENCES CATEGORY.id
)

Infine la parte di utenti, ruoli e permessi che sarà gestita principalmente da librerie, e la parte delle zone agenti che saranno associate agli utenti definiti come agenti tramite i ruoli.

USER (
    id int PK,
    username string,
    email string,
    password string
)

ROLE (
    id int PK,
    key string,
    description string
)

PERMISSION (
    id int PK,
    key string,
    description string
)

AGENT_ZONE (
    id int PK,
    erpID string,
    FK owner REFERENCES USER.id
)
