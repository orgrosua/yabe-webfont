import 'awesome-notifications/dist/style.css';
import AWN from 'awesome-notifications';

export const useNotifier = (options = {}) => {
    return new AWN(options);
}