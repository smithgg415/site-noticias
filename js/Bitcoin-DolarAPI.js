async function getDollarValue() {
    try {
        const response = await fetch('https://api.exchangerate-api.com/v4/latest/USD');
        const data = await response.json();
        return data.rates.BRL;
    } catch (error) {
        console.error(error);
        document.getElementById('error').innerText = 'Erro ao buscar a cotação do dólar.';
        return null;
    }
}

async function getBitcoinValue() {
    try {
        const response = await fetch('https://api.coindesk.com/v1/bpi/currentprice/BTC.json');
        const data = await response.json();
        return data.bpi.USD.rate_float;  
    } catch (error) {
        console.error(error);
        document.getElementById('error').innerText = 'Erro ao buscar a cotação do bitcoin.';
        return null;
    }
}

function formatCurrency(value, currency = 'BRL') {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: currency,
    }).format(value);
}

async function fetchValues() {
    const dollar = await getDollarValue();
    const bitcoin = await getBitcoinValue();

    if (dollar !== null && bitcoin !== null) {
        const bitcoinInBRL = bitcoin * dollar; 
        document.getElementById('dollar').querySelector('.value').innerText = formatCurrency(dollar);
        document.getElementById('dollar').querySelector('.currency').innerText = `R$ ${formatCurrency(dollar)}`;
        document.getElementById('bitcoin').querySelector('.value').innerText = `$ ${formatCurrency(bitcoin, 'USD')}`;
        document.getElementById('bitcoin').querySelector('.currency').innerText = `R$ ${formatCurrency(bitcoinInBRL)}`;
    }
}

fetchValues();

setInterval(fetchValues, 60000);
