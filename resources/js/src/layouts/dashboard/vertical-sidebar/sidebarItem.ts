// icons
import {
  QuestionOutlined,
  DashboardOutlined,
  ChromeOutlined,
  LoginOutlined,
  ProfileOutlined,
  CrownOutlined,
  UserOutlined
} from '@ant-design/icons-vue';

export interface menu {
  header?: string;
  title?: string;
  icon?: object;
  to?: string;
  divider?: boolean;
  chip?: string;
  chipColor?: string;
  chipVariant?: string;
  chipIcon?: string;
  children?: menu[];
  disabled?: boolean;
  type?: string;
  subCaption?: string;
}

const sidebarItem: menu[] = [
  { header: 'Navigation' },
  {
    title: 'Dashboard',
    icon: DashboardOutlined,
    to: '/dashboard'
  },
  { header: 'User' },
  {
    title: 'Daftar Karyawan',
    icon: UserOutlined,
    to: '/user'
  },
  { header: 'Utilities' },
  {
    title: 'Generate Sertifikat',
    icon: CrownOutlined,
    to: '/generate-sertifikat'
  },
  { header: 'Support' },
  {
    title: 'Sample Page',
    icon: ChromeOutlined,
    to: '/sample-page'
  },
  {
    title: 'Documentation',
    icon: QuestionOutlined,
    to: 'https://codedthemes.gitbook.io/mantis-vuetify/',
    type: 'external',
    chip: 'gitbook',
    chipColor: 'secondary',
    chipVariant: 'flat'
  }
];

export default sidebarItem;
