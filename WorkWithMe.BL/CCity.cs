using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL; 

namespace WorkWithMe.BL
{
   public class CCity
    {

        public Guid Id { get; set; }
        public string CityName { get; set; }

        public CCity() { }

        public CCity(Guid id, string cityName)
        {
            Id = id;
            CityName = cityName;
        }

        public void Create()
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();
                tblCity c = new tblCity();
                c.Id = Guid.NewGuid();
                c.CityName = CityName;

                oDC.tblCities.InsertOnSubmit(c);
                oDC.SubmitChanges();

            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        public void Update()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblCity city = (from c in oDC.tblCities where c.Id == Id select c).FirstOrDefault();

                city.Id = Id;
                city.CityName = CityName;

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblCity city = (from c in oDC.tblCities where c.Id == Id select c).FirstOrDefault();

                oDC.tblCities.DeleteOnSubmit(city);
                oDC.SubmitChanges();
            }
        }


    }
}
