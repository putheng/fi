var allLocationsx;

$(document).ready(function() {
    $.get('/data', {}, function(data){
        allLocations = data.data;
    });
});


var allLocations = [
    {
        name: "Sihanouk Hospital Center of Hope",
        lat: "11.5669393",
        lng: "104.9103357",
        address: "Naulo Ghumti, Pokhara, Kaski",
        city: "ផ្លូវលេខ ១៣៤, រាជធានី​ភ្នំពេញ",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "Ang Duong Hospital",
        lat: "11.5716662",
        lng: "104.9212499",
        address: "AMDA, Itahari, Sunsari",
        city: "Aaitabare, NTC Road, 100-meter south from Nepal telecom office. ",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "Central Hospital",
        lat: "11.5668804",
        lng: "104.9204223",
        address: "SACTS, Thapathali, Kathmandu, Nepal",
        city: "Near Norvic Hospital opposite of Ekta Books Store ",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "Royal Phnom Penh Hospital",
        lat: "11.567984",
        lng: "104.8844966",
        address: "SACTS, Lagankhel, Lalitpur",
        city: "Gobinda Bhawan 3rd Floor, near Midat Hospital, next to Nepal Mandal Television, Lagankhel",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "សម្ភព សោភា",
        lat: "11.5575587",
        lng: "104.9143356",
        address: "GWP, Hetauda, Makawanpur, Nepal",
        city: "In-front of Rani Darbar, Main road, Hetauda-4 Makawanpur",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "Calmette Hospital",
        lat: "11.5810636",
        lng: "104.9137138",
        address: "NAMUNA, Ittabhatti line, Kalika Nagar, Malika path, Butwal-9, Rupandehi",
        city: "In-front of Khatri Nursing Home(Behind new Buspark)",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "Hospital Beyond Boundries",
        lat: "11.5448781",
        lng: "104.8899781",
        address: "AMDA, Birtamode, Jhapa, Nepal",
        city: "Birtamod, Mukti Chowk, 100 meter South from bolbom petrol pump ",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "Victoria International Hospital",
        lat: "11.5760599",
        lng: "104.8896355",
        address: "NNSWA, Chatakpur, Dhangadhi-5, Kailali",
        city: "Chatakpur, Parking side (behind) of CP Hospital, Dhangadhi",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    },
    {
        name: "Dr Agarwal'e Eye Hospital",
        lat: "11.5682906",
        lng: "104.8928851",
        address: "Buspark, Jyotinagar,-5,Near Kathmandu Bus Park, opposite Nepal Electricity Office, Nepalgunj, Banke",
        city: "Buspark, Jyotinagar,-5,Near Kathmandu Bus Park, opposite Nepal Electricity Office, Nepalgunj, Banke",
        state: "PP",
        zip: "12855",
        phone: "(555) 555-5555"
    }
];