// formats date to a string that represnts how long ago the date is from current present date
// common usages for those formatted dates are in forums and blog posts / last update of some content
// the min/max represent the days range for a single label
export function formatTimestampFromNow(timestamp){
    let ranges = [
        {
            'min_value': 699,
            'max_value': 9999999999,
            'label': 'לפני כמה שנים'
        },
        {
            'min_value': 351,
            'max_value': 700,
            'label': 'לפני שנה'
        },
        {
            'min_value': 60,
            'max_value': 350,
            'label': 'לפני כמה חודשים'
        },
        {
            'min_value': 30,
            'max_value': 59,
            'label': 'לפני חודש'
        },
        {
            'min_value': 12,
            'max_value': 29,
            'label': 'לפני שבועיים'
        },
        {
            'min_value': 7,
            'max_value': 11,
            'label': 'לפני שבוע'
        },
        {
            'min_value': 3,
            'max_value': 6,
            'label': 'לפני כמה ימים'
        },
        {
            'min_value': 2,
            'max_value': 2,
            'label': 'שלשום'
        },
        {
            'min_value': 1,
            'max_value': 1,
            'label': 'אתמול'
        },
        {
            'min_value': 0,
            'max_value': 0,
            'label': 'היום'
        }
    ]

    try{
        let current     = new Date();
        let past        = new Date(timestamp * 1000);
        let pastHours   = past.getHours();
        let pastMinutes = past.getMinutes();

        pastHours   = pastHours < 10 ? `0${pastHours.toString()}` : pastHours.toString();
        pastMinutes = pastMinutes < 10 ? `0${pastMinutes.toString()}` : pastMinutes.toString();

        const diffInMs   = new Date(current) - new Date(past)
        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
        const found_range = ranges.filter(obj => diffInDays >= obj.min_value && diffInDays <= obj.max_value)

        if(found_range.length > 0){
            return `${found_range[0].label} בשעה ${pastHours}:${pastMinutes}`
        }

        return `בשעה ${pastHours}:${pastMinutes}`
    }
    catch{
        return 'בשעה לא ידועה'
    }
}
