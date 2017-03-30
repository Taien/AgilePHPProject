using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL;

namespace WorkWithMe.BL
{
    public class CCityStateInfo
    {
        public string CityName { get; set; }
        public string StateName { get; set; }
        public int Zip { get; set; }

        public CCityStateInfo()
        {

        }

        public CCityStateInfo(int zip, string cityName, string stateName)
        {
            Zip = zip;
            CityName = cityName;
            StateName = stateName;
        }

        public CCityStateInfo(int zip)
        {
            Zip = zip;
        }

        public void GetInfo()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblZip zip = (from z in oDC.tblZips where z.Id == Zip select z).FirstOrDefault();

                tblCity city = (from c in oDC.tblCities where c.Id == zip.CityId select c).FirstOrDefault();

                tblState state = (from s in oDC.tblStates where s.Id == zip.StateId select s).FirstOrDefault();

                CityName = city.CityName;
                StateName = state.StateName;
            }
        }
    }
}
